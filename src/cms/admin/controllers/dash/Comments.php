<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\controllers\dash;

use cms\admin\controllers\BaseController;

/**
 * Admin panel comments controller.
 *
 * @author Joe J. Howard
 */
class Comments extends BaseController
{
    /**
     * {@inheritdoc}
     */
    protected $template = 'dash/comments.php';

    /**
     * {@inheritdoc}
     */
    protected $pageTitle = 'Comments';
}
