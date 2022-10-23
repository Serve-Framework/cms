<?php

namespace cms\router\routes;

use serve\http\route\Router;
use serve\config\Config;

/**
 * CMS base routes.
 *
 * @author Joe J. Howard
 */
abstract class RoutesBase
{
    /**
     * Array of routes to apply.
     *
     * @var array
     */
    protected $routes;

    /**
     * Router.
     *
     * @var \serve\http\router\Router
     */
    protected $router;

    /**
     * Config.
     *
     * @var \serve\config\Config
     */
    protected $config;

    /**
     * CMS blog prefix.
     *
     * @var string
     */
    protected $blogPrefix;

    /**
     * Constructor.
     * 
     * @param \serve\http\router\Router $router Router
     * @param \serve\config\Config      $config Config
     *
     */
    public function __construct(Router $router, Config $config)
    {
        $this->router = $router;

        $this->config = $config;

        $this->blogPrefix = !empty($config->get('cms.blog_location')) ? '/' . $config->get('cms.blog_location') : '';
    }

    /**
     * Apply routes to router.
     * 
     */
    public function apply() : void
    {
        if ($this->shouldRoute())
        {
            $this->preFilterRoutes();

            foreach ($this->routes as $route)
            {
                $method = $route['method'];

                $this->router->{$method}($route['route'], $route['controller'], $route['model']);
            }
        }
    }

    /**
     * Should we apply the routes?
     * 
     * @return bool
     */
    abstract protected function shouldRoute() : bool;

    /**
     * Apply any conditional filtering to routes.
     * 
     */
    abstract protected function preFilterRoutes() : void;
}


