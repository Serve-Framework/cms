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
 * Admin panel forgot password model.
 *
 * @author Joe J. Howard
 */
class ResetPassword extends BaseModel
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
        if (!$this->isLoggedIn())
        {
            // Get the token in the url
            $token = $this->Request->queries('token');

            // If no token was given 404
            if (!$token || trim($token) === '' || $token === 'null')
            {
                return false;
            }

            // The user just updated their password they can load the page once
            // to show the success message
            if ($this->Response->session()->get('serve_updated_password'))
            {
                $this->Response->session()->remove('serve_updated_password');

                return [];
            }

            // Get the user based on their token
            $user = $this->UserManager->provider()->byKey('serve_password_key', $token, true);

            // Add the token to their session
            if ($user)
            {
                $this->Response->session()->set('serve_password_key', $token);

                return [];
            }
        }

        return false;
    }

    /**
     * Parse a login request via POST.
     *
     * @return array
     */
    private function validatePost(): array
    {
        $post  = $this->post;
        $rules =
        [
            'password'  => ['required', 'max_length(50)', 'min_length(4)'],
        ];
        $filters =
        [
            'password' => ['trim'],
        ];

        $validator = $this->container->Validator->create($post, $rules, $filters);

        if (!$validator->isValid())
        {
            $errors = $validator->getErrors();

            return $this->postMessage('warning', array_shift($errors));
        }

        $post = $validator->filter();

        // Make sure the user's token is in the session and they match
        $token = $this->Response->session()->get('serve_password_key');

        if (!$token)
        {
            return $this->postMessage('danger', 'There was an error processing your request.');
        }

        // Get the user based on their token
        $user = $this->UserManager->provider()->byKey('serve_password_key', $token, true);

        if ($user)
        {
            if ($this->Gatekeeper->resetPassword($post['password'], $token))
            {
                $this->Response->session()->remove('serve_password_key');

                $this->Response->session()->set('serve_updated_password', true);

                return $this->postMessage('success', 'Your password was successfully reset.');
            }
        }

        return $this->postMessage('danger', 'There was an error processing your request.');
    }
}