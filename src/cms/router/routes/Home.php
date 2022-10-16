<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

use cms\router\controllers\Content as ContentController;
use cms\router\models\Home as HomeModel;
use cms\router\models\Homepage as HomepageModel;

/**
 * CMS Application home routes.
 *
 * @author Joe J. Howard
 */

// Homepage
$router->get('/',             ContentController::class . '@apply', HomeModel::class);
$router->get('/page/(:num)/', ContentController::class . '@apply', HomeModel::class);
$router->get('/feed/',        ContentController::class . '@rss',   HomeModel::class);
$router->get('/feed/rss/',    ContentController::class . '@rss',   HomeModel::class);
$router->get('/feed/atom/',   ContentController::class . '@rss',   HomeModel::class);
$router->get('/feed/rdf/',    ContentController::class . '@rss',   HomeModel::class);

// Blog Homepage
if (!empty($blogPrefix))
{
	$router->get("$blogPrefix/",             ContentController::class . '@apply', HomepageModel::class);
	$router->get("$blogPrefix/page/(:num)/", ContentController::class . '@apply', HomepageModel::class);
	$router->get("$blogPrefix/feed/",        ContentController::class . '@rss',   HomepageModel::class);
	$router->get("$blogPrefix/feed/rss/",    ContentController::class . '@rss',   HomepageModel::class);
	$router->get("$blogPrefix/feed/atom/",   ContentController::class . '@rss',   HomepageModel::class);
	$router->get("$blogPrefix/feed/rdf/",    ContentController::class . '@rss',   HomepageModel::class);
}
