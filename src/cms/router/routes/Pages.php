<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

use cms\router\controllers\Content as ContentController;
use cms\router\models\Page as PageModel;

/**
 * CMS Application pages routes.
 *
 * @author Joe J. Howard
 */

$router->get('/(:any)/',          ContentController::class . '@apply', PageModel::class);
$router->get('/(:any)/feed/',     ContentController::class . '@rss',   PageModel::class);
$router->get('/(:any)/feed/rss',  ContentController::class . '@rss',   PageModel::class);
$router->get('/(:any)/feed/atom', ContentController::class . '@rss',   PageModel::class);
$router->get('/(:any)/feed/rdf',  ContentController::class . '@rss',   PageModel::class);
