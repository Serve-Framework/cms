<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\controllers\dash;

use cms\admin\controllers\BaseController;

/**
 * Admin panel error logs controller.
 *
 * @author Joe J. Howard
 */
class ErrorLogs extends BaseController
{
    /**
     * {@inheritdoc}
     */
    protected $template = 'dash/error-logs.php';

    /**
     * {@inheritdoc}
     */
    protected $pageTitle = 'Error Logs';
}