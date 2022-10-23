<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\controllers\dash;

use cms\admin\controllers\BaseController;

/**
 * Admin panel custom posts controller.
 *
 * @author Joe J. Howard
 */
class Settings extends BaseController
{
    /**
     * {@inheritdoc}
     */
    protected $template = 'dash/settings.php';

    /**
     * {@inheritdoc}
     */
    protected $pageTitle = 'Pages';
}