<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\routes;

use cms\router\controllers\Content as Controller;
use cms\router\models\HomePage as Model;

/**
 * CMS home blog routes.
 *
 * @author Joe J. Howard
 */
class HomePage extends RoutesBase
{
    /**
     * {@inheritdoc}
     */
    protected $routes =
    [
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/',
            'controller' => Controller::class . '@apply',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/page/(:num)/',
            'controller' => Controller::class . '@apply',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/feed/',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/feed/rss/',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/feed/atom/',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/feed/rdf.',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        
    ];

    /**
     * {@inheritdoc}
     */
    protected function shouldRoute(): bool
    {
        return !empty($this->blogPrefix) && $this->blogPrefix !== '';
    }

    /**
     * {@inheritdoc}
     */
    protected function preFilterRoutes(): void
    {
        foreach($this->routes as $i => $route)
        {
            $this->routes[$i]['route'] = str_replace('blog_prefix', $this->blogPrefix, $this->routes[$i]['route']);
        }
    }
}
