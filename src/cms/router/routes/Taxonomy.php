<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

use cms\router\controllers\Content as ContentController;
use cms\router\models\Category as CategoryModel;
use cms\router\models\Tag as TagModel;
use cms\router\models\Author as AuthorModel;

/**
 * CMS Application taxonomy routes.
 *
 * @author Joe J. Howard
 */

// Category
if ($config->get('cms.route_categories') === true)
{

	$router->get("$blogPrefix/category/(:all)/feed/rss/",    ContentController::class . '@rss',   CategoryModel::class);
	$router->get("$blogPrefix/category/(:all)/feed/atom/",   ContentController::class . '@rss',   CategoryModel::class);
	$router->get("$blogPrefix/category/(:all)/feed/rdf/",    ContentController::class . '@rss',   CategoryModel::class);
	$router->get("$blogPrefix/category/(:all)/page/(:num)/", ContentController::class . '@apply', CategoryModel::class);
	$router->get("$blogPrefix/category/(:all)/feed/",        ContentController::class . '@rss',   CategoryModel::class);
	$router->get("$blogPrefix/category/(:all)/",             ContentController::class . '@apply', CategoryModel::class);
}

// Tag
if ($config->get('cms.route_tags') === true)
{
	$router->get("$blogPrefix/tag/(:any)/",             ContentController::class . '@apply', TagModel::class);
	$router->get("$blogPrefix/tag/(:any)/page/(:num)/", ContentController::class . '@apply', TagModel::class);
	$router->get("$blogPrefix/tag/(:any)/feed/",        ContentController::class . '@rss',   TagModel::class);
	$router->get("$blogPrefix/tag/(:any)/feed/rss/",    ContentController::class . '@rss',   TagModel::class);
	$router->get("$blogPrefix/tag/(:any)/feed/atom/",   ContentController::class . '@rss',   TagModel::class);
	$router->get("$blogPrefix/tag/(:any)/feed/rdf/",    ContentController::class . '@rss',   TagModel::class);
}

// Author
if ($config->get('cms.route_authors') === true)
{
	$router->get("$blogPrefix/author/(:any)/",             ContentController::class . '@apply', AuthorModel::class);
	$router->get("$blogPrefix/author/(:any)/page/(:num)/", ContentController::class . '@apply', AuthorModel::class);
	$router->get("$blogPrefix/author/(:any)/feed/",        ContentController::class . '@rss',   AuthorModel::class);
	$router->get("$blogPrefix/author/(:any)/feed/rss/",    ContentController::class . '@rss',   AuthorModel::class);
	$router->get("$blogPrefix/author/(:any)/feed/atom/",   ContentController::class . '@rss',   AuthorModel::class);
	$router->get("$blogPrefix/author/(:any)/feed/rdf/",    ContentController::class . '@rss',   AuthorModel::class);
}
