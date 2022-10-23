<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\controllers\account;

use cms\admin\controllers\BaseController;

/**
 * Admin panel forgot password controller.
 *
 * @author Joe J. Howard
 */
class ForgotPassword extends BaseController
{
    /**
     * {@inheritdoc}
     */
    protected $template = 'account/forgot-password.php';

    /**
     * {@inheritdoc}
     */
    protected $pageTitle = 'Forgot Password';
}
