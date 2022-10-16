<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

use cms\router\controllers\Content as ContentController;
use cms\router\models\Attachment as AttachmentModel;

/**
 * CMS Application attachment routes.
 *
 * @author Joe J. Howard
 */

// Attachments
if ($config->get('cms.route_attachments') === true)
{
	$router->get('/attachment/(:any)/',           ContentController::class . '@apply',  AttachmentModel::class);
	$router->get('/attachment/(:any)/feed/',      ContentController::class . '@rss',   AttachmentModel::class);
	$router->get('/attachment/(:any)/feed/rss/',  ContentController::class . '@rss',   AttachmentModel::class);
	$router->get('/attachment/(:any)/feed/atom/', ContentController::class . '@rss',   AttachmentModel::class);
	$router->get('/attachment/(:any)/feed/rdf/',  ContentController::class . '@rss',   AttachmentModel::class);
}
