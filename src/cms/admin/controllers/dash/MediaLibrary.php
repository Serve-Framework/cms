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
class MediaLibrary extends BaseController
{
    /**
     * {@inheritdoc}
     */
    protected $template = 'dash/media-library.php';

    /**
     * {@inheritdoc}
     */
    protected $pageTitle = 'Media Library';
}