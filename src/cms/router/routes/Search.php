<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

use cms\router\controllers\Content;
use cms\router\models\Search;

/**
 * CMS Application search routes.
 *
 * @author Joe J. Howard
 */
$router->get('/search-results/',             Content::class . '@apply', Search::class);
$router->get('/search-results/page/(:num)/', Content::class . '@apply', Search::class);
