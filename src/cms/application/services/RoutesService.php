<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\application\services;

use serve\application\services\Service;
use serve\utility\Callback;
use cms\router\routes\Admin;
use cms\router\routes\Attachment;
use cms\router\routes\Comments;
use cms\router\routes\Home;
use cms\router\routes\HomePage;
use cms\router\routes\Pages;
use cms\router\routes\Posts;
use cms\router\routes\Search;
use cms\router\routes\Sitemap;
use cms\router\routes\Categories;
use cms\router\routes\Tags;
use cms\router\routes\Authors;

/**
 * CMS routes service.
 *
 * @author Joe J. Howard
 */
class RoutesService extends Service
{
    /**
     * CMS Routes.
     * 
     * @var array
     */
    protected $routes =
    [
        Admin::class,
        Attachment::class,
        Comments::class,
        Home::class,
        HomePage::class,
        Pages::class,
        Posts::class,
        Search::class,
        Sitemap::class,
        Categories::class,
        Tags::class,
        Authors::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        foreach($this->routes as $routeClass)
        {
            $route = Callback::newClass($routeClass, [$this->container->Router, $this->container->Config, $this->container->Database->connection()->builder()]);

            $route->apply();
        }
    }
}
