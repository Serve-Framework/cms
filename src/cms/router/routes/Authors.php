<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\routes;

use cms\router\controllers\Content as Controller;
use cms\router\models\Author as Model;

/**
 * CMS authors routes.
 *
 * @author Joe J. Howard
 */
class Authors extends RoutesBase
{
    /**
     * {@inheritdoc}
     */
    protected $routes =
    [
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/author/(:any)/',
            'controller' => Controller::class . '@apply',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/author/(:any)/page/(:num)/',
            'controller' => Controller::class . '@apply',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/author/(:any)/feed/',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/author/(:any)/feed/rss/',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/author/(:any)/feed/atom/',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/author/(:any)/feed/rdf/',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        
    ];

    /**
     * {@inheritdoc}
     */
    protected function shouldRoute(): bool
    {
        return $this->config->get('cms.route_authors') === true;
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
