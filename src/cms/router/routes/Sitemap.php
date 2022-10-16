<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

use cms\router\controllers\Sitemap as Controller;
use cms\router\models\Sitemap as Model;

/**
 * CMS Application sitemap route.
 *
 * @author Joe J. Howard
 */

// Sitemap
$router->get('/' . $config->get('cms.sitemap_route'), Controller::class . '@load', Model::class);
