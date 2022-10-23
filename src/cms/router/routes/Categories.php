<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\routes;

use cms\router\controllers\Content as Controller;
use cms\router\models\Category as Model;

/**
 * CMS categories routes.
 *
 * @author Joe J. Howard
 */
class Categories extends RoutesBase
{
    /**
     * {@inheritdoc}
     */
    protected $routes =
    [
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/category/(:all)/feed/rss',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
         [
            'method'     => 'get',
            'route'      => 'blog_prefix/category/(:all)/feed/atom/',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/category/(:all)/feed/rdf/',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/category/(:all)/page/(:num)/',
            'controller' => Controller::class . '@apply',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/category/(:all)/feed/',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/category/(:all)/',
            'controller' => Controller::class . '@apply',
            'model'      => Model::class
        ],
    ];

    /**
     * {@inheritdoc}
     */
    protected function shouldRoute(): bool
    {
        return $this->config->get('cms.route_categories') === true;
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
