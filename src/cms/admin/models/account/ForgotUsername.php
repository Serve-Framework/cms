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
 * Admin panel forgot username model.
 *
 * @author Joe J. Howard
 */
class ForgotUsername extends BaseModel
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
    private function validatePost(): array
    {
        $rules =
        [
            'email'  => ['required', 'email'],
        ];
        $filters =
        [
            'email' => ['trim', 'email'],
        ];

        $validator = $this->container->Validator->create($this->post, $rules, $filters);

        $post = $validator->filter();

        if ($validator->isValid())
        {
            $user = $this->UserManager->provider()->byEmail($post['email']);

            if ($user || ($user->role !== 'administrator' && $user->role !== 'writer'))
            {
                $this->Gatekeeper->forgotUsername($post['email']);
            }
        }

        return $this->postMessage('success', 'If a user is registered under that email address, they were sent an email with their username.');
    }
}