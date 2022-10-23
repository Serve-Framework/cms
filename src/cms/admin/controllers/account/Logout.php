<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\controllers\account;

use cms\admin\controllers\BaseController;

/**
 * Admin panel logout controller.
 *
 * @author Joe J. Howard
 */
class Logout extends BaseController
{
    /**
     * {@inheritdoc}
     */
    protected $template = 'account/logout.php';

    /**
     * {@inheritdoc}
     */
    protected $pageTitle = 'Logout';
}
