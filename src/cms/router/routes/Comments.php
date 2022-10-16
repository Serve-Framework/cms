<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

use cms\router\controllers\Comments as CommentsController;
use cms\router\models\Comments as CommentsModel;

/**
 * CMS Application comments routes.
 *
 * @author Joe J. Howard
 */

// Ajax Post Comments
if ($config->get('cms.enable_comments') === true)
{
	$router->post('/comments/', CommentsController::class . '@addComment', CommentsModel::class);
}
