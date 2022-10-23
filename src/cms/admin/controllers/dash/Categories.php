<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\controllers\dash;

use cms\admin\controllers\BaseController;

/**
 * Admin panel categories controller.
 *
 * @author Joe J. Howard
 */
class Categories extends BaseController
{
    /**
     * {@inheritdoc}
     */
    protected $template = 'dash/categories.php';

    /**
     * {@inheritdoc}
     */
    protected $pageTitle = 'Categories';
}
