<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\models\account;

use cms\admin\models\BaseModel;

/**
 * Admin panel logout model.
 *
 * @author Joe J. Howard
 */
class Logout extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public function onPOST(): array|false
    {
        return false;
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
            $this->Gatekeeper->logout();

            $this->Response->redirect($this->Request->environment()->HTTP_HOST);

            return true;
        }

        return false;
    }
}
