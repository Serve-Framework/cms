<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\controllers\dash;

use cms\admin\controllers\BaseController;

/**
 * Admin panel blank page controller.
 *
 * @author Joe J. Howard
 */
class BlankPage extends BaseController
{
    /**
     * {@inheritdoc}
     */
    protected $template = 'dash/blank-page.php';

    /**
     * {@inheritdoc}
     */
    protected $pageTitle;
}
