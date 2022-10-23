<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\controllers\account;

use cms\admin\controllers\BaseController;

/**
 * Admin panel reset password controller.
 *
 * @author Joe J. Howard
 */
class ResetPassword extends BaseController
{
    /**
     * {@inheritdoc}
     */
    protected $template = 'account/reset-password.php';

    /**
     * {@inheritdoc}
     */
    protected $pageTitle = 'Reset Password';
}
