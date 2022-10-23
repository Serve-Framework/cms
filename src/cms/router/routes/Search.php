<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\routes;

use cms\router\controllers\Content;
use cms\router\models\Search as Model;

/**
 * CMS search routes.
 *
 * @author Joe J. Howard
 */
class Search extends RoutesBase
{
    /**
     * {@inheritdoc}
     */
    protected $routes =
    [
        [
            'method'     => 'get',
            'route'      => '/search-results/',
            'controller' => Content::class . '@apply',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => '/search-results/page/(:num)/',
            'controller' => Content::class . '@apply',
            'model'      => Model::class
        ],
    ];

    /**
     * {@inheritdoc}
     */
    protected function shouldRoute(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function preFilterRoutes(): void
    {
    
    }
}