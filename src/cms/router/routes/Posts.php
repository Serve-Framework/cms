<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\routes;

use cms\router\controllers\Content as Controller;
use cms\router\models\Single as Model;

/**
 * CMS posts routes.
 *
 * @author Joe J. Howard
 */
class Posts extends RoutesBase
{
    /**
     * {@inheritdoc}
     */
    protected $routes =
    [
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/permalinks/feed/rss/',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/permalinks/feed/atom/',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
         [
            'method'     => 'get',
            'route'      => 'blog_prefix/permalinks/feed/rdf.',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/permalinks/feed/',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => 'blog_prefix/permalinks/',
            'controller' => Controller::class . '@apply',
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
        $permalinkRoute = trim($this->config->get('cms.permalinks_route'), '/');

        foreach($this->routes as $i => $route)
        {
            $this->routes[$i]['route'] = str_replace('blog_prefix', $this->blogPrefix, str_replace('permalinks', $permalinkRoute, $this->routes[$i]['route']));
        }
    }
}