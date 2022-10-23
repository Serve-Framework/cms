<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\models\account;

use cms\admin\models\BaseModel;
use serve\http\response\exceptions\InvalidTokenException;
use serve\http\response\exceptions\RequestException;

/**
 * Admin panel login model.
 *
 * @author Joe J. Howard
 */
class Login extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public function onPOST(): array|false
    {
        if (!isset($this->post['access_token']) || !$this->Gatekeeper->verifyToken($this->post['access_token']))
        {
            throw new InvalidTokenException('Bad Admin Panel POST Request. The CSRF token was either not provided or was invalid.');
        }

        if (!$this->isLoggedIn())
        {
            return $this->validatePost();
        }

        throw new RequestException(500, 'Bad Admin Panel POST Request. The POST data was either not provided or was invalid.');
    }

    /**
     * {@inheritdoc}
     */
    public function onAJAX(): array|false
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function onGET(): array|false
    {        
        if ($this->isLoggedIn())
        {
            $this->Response->redirect($this->Request->environment()->HTTP_HOST . '/admin/posts/');

            return false;
        }

        return [];
    }

    /**
     * Parse a login request via POST.
     *
     * @return array
     */
    protected function validatePost(): array
    {
        $rules =
        [
            'username'  => ['required', 'max_length(50)', 'min_length(4)'],
            'password'  => ['required', 'max_length(50)', 'min_length(4)'],
        ];
        $filters =
        [
            'username' => ['trim', 'string'],
            'password' => ['trim'],
        ];

        $validator = $this->container->Validator->create($this->post, $rules, $filters);

        if (!$validator->isValid())
        {
            return $this->postMessage('danger', 'Either the username or password you entered was incorrect.');
        }

        // Sanitize and validate the POST
        $post = $validator->filter();

        $user = $this->UserManager->provider()->byUsername($post['username']);

        if (!$user || ($user->role !== 'administrator' && $user->role !== 'writer'))
        {
            return $this->postMessage('danger', 'Either the username or password you entered was incorrect.');
        }

        $login = $this->Gatekeeper->login($post['username'], $post['password']);

        if ($login === $this->Gatekeeper::LOGIN_ACTIVATING)
        {
            return $this->postMessage('warning', 'Your account has not yet been activated.');
        }
        elseif ($login === $this->Gatekeeper::LOGIN_LOCKED)
        {
            return $this->postMessage('warning', 'That account has been temporarily locked.');
        }
        elseif ($login === $this->Gatekeeper::LOGIN_BANNED)
        {
            return $this->postMessage('warning', 'That account has been permanently suspended.');
        }
        elseif ($login === true)
        {
            $this->Response->redirect($this->Request->environment()->HTTP_HOST . '/admin/posts/');
        }

        return $this->postMessage('danger', 'Either the username or password you entered was incorrect.');
    }
}
