<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\routes;

use cms\router\controllers\Sitemap as Controller;
use cms\router\models\Sitemap as Model;

/**
 * CMS search routes.
 *
 * @author Joe J. Howard
 */
class Sitemap extends RoutesBase
{
    /**
     * {@inheritdoc}
     */
    protected $routes =
    [
        [
            'method'     => 'get',
            'route'      => '/sitemap_path/',
            'controller' => Content::class . '@load',
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
        $sitemap = trim($this->config->get('cms.sitemap_route'), '/');

        $this->routes[0]['route'] = str_replace('sitemap_path', $sitemap, $this->routes[0]['route']);
    }
}