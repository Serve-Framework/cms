<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\controllers\account;

use cms\admin\controllers\BaseController;

/**
 * Admin panel forgot username controller.
 *
 * @author Joe J. Howard
 */
class ForgotUsername extends BaseController
{
    /**
     * {@inheritdoc}
     */
    protected $template = 'account/forgot-username.php';

    /**
     * {@inheritdoc}
     */
    protected $pageTitle = 'Forgot Username';
}
