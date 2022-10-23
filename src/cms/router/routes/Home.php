<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\routes;

use cms\router\controllers\Content as Controller;
use cms\router\models\Home as Model;

/**
 * CMS home routes.
 *
 * @author Joe J. Howard
 */
class Home extends RoutesBase
{
    /**
     * {@inheritdoc}
     */
    protected $routes =
    [
        [
            'method'     => 'get',
            'route'      => '/',
            'controller' => Controller::class . '@apply',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => '/page/(:num)/',
            'controller' => Controller::class . '@apply',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => '/feed/',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => '/feed/rss/',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => '/feed/atom/',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => '/feed/rdf.',
            'controller' => Controller::class . '@rss',
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