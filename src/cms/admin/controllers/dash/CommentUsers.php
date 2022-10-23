<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\controllers\dash;

use cms\admin\controllers\BaseController;

/**
 * Admin panel comment users controller.
 *
 * @author Joe J. Howard
 */
class CommentUsers extends BaseController
{
    /**
     * {@inheritdoc}
     */
    protected $template = 'dash/comment-users.php';

    /**
     * {@inheritdoc}
     */
    protected $pageTitle = 'Commentors';
}
