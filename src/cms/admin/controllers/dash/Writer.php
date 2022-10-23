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
class Writer extends BaseController
{
    /**
     * {@inheritdoc}
     */
    protected $template = 'dash/writer.php';

    /**
     * {@inheritdoc}
     */
    protected $pageTitle = 'Writer';
}