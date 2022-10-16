<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

use cms\router\controllers\Content as ContentController;
use cms\router\models\Single as SingleModel;

/**
 * CMS Application posts routes.
 *
 * @author Joe J. Howard
 */

// Posts
$permalinkRoute = ltrim(rtrim($config->get('cms.permalinks_route'), '/'), '/') . '/';

$router->get($blogPrefix . '/' . $permalinkRoute . 'feed/rss/',  ContentController::class . '@rss',  SingleModel::class);
$router->get($blogPrefix . '/' . $permalinkRoute . 'feed/atom/', ContentController::class . '@rss',  SingleModel::class);
$router->get($blogPrefix . '/' . $permalinkRoute . 'feed/rdf/',  ContentController::class . '@rss',  SingleModel::class);
$router->get($blogPrefix . '/' . $permalinkRoute . 'feed/',      ContentController::class . '@rss',  SingleModel::class);
$router->get($blogPrefix . '/' . $permalinkRoute,                ContentController::class . '@apply', SingleModel::class);
