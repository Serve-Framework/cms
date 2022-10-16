<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin;

use cms\admin\controllers\Dashboard as DashController;
use cms\router\controllers\Content as ContentController;
use cms\router\models\CustomPost as CustomPostModel;
use cms\router\models\CustomPosts as CustomPostsModel;
use cms\admin\models\Posts as PostsModel;
use serve\ioc\ContainerAwareTrait;
use serve\utility\Arr;
use serve\utility\Humanizer;
use serve\utility\Str;

/**
 * Admin access.
 *
 * @author Joe J. Howard
 */
class Admin
{
    use ContainerAwareTrait;

    /**
     * Register a custom post type.
     *
     * @param string $title Custom post type title
     * @param string $type  Custom post type
     * @param string $icon  Icon to be used in admin panel sidebar
     * @param string $route Route for front end
     */
    public function registerPostType(string $title, string $type, string $icon, string $route): void
    {
        // Sanitize the type
        $slug = Str::slug($type);

        // Sanitize the route
        $route = trim($route, '/');

        // Is this page being requested in the admin panel ?
        // Is this page being requested ?
        $requestSlug = Str::getAfterLastChar($this->Request->environment()->REQUEST_PATH, '/');
        $isPage      = $slug === $requestSlug;

        // Add the admin panel route
        $this->Router->get("/admin/{$slug}/",        DashController::class . '@customPostType', PostsModel::class);
        $this->Router->get("/admin/{$slug}/(:all)",  DashController::class . '@customPostType', PostsModel::class);
        $this->Router->post("/admin/{$slug}/",       DashController::class . '@customPostType', PostsModel::class);
        $this->Router->post("/admin/{$slug}/(:all)", DashController::class . '@customPostType', PostsModel::class);

        // Add the front-end routes to all posts
        $types = Humanizer::pluralize(strtolower($type));

        $this->Router->get('/' . $types . '/feed/rss/',  ContentController::class . '@rss',   [CustomPostsModel::class, "home-{$slug}"]);
        $this->Router->get('/' . $types . '/feed/atom/', ContentController::class . '@rss',   [CustomPostsModel::class, "home-{$slug}"]);
        $this->Router->get('/' . $types . '/feed/rdf/',  ContentController::class . '@rss',   [CustomPostsModel::class, "home-{$slug}"]);
        $this->Router->get('/' . $types . '/feed/',      ContentController::class . '@rss',   [CustomPostsModel::class, "home-{$slug}"]);
        $this->Router->get('/' . $types,                 ContentController::class . '@apply', [CustomPostsModel::class, "home-{$slug}"]);

        // Add the front-end routes to custom post
        $this->Router->get('/' . $route . '/feed/rss/',  ContentController::class . '@rss',   [CustomPostModel::class, "single-{$slug}"]);
        $this->Router->get('/' . $route . '/feed/atom/', ContentController::class . '@rss',   [CustomPostModel::class, "single-{$slug}"]);
        $this->Router->get('/' . $route . '/feed/rdf/',  ContentController::class . '@rss',   [CustomPostModel::class, "single-{$slug}"]);
        $this->Router->get('/' . $route . '/feed/',      ContentController::class . '@rss',   [CustomPostModel::class, "single-{$slug}"]);
        $this->Router->get('/' . $route,                 ContentController::class . '@apply', [CustomPostModel::class, "single-{$slug}"]);

       
        // Add the custom post type to the config
        // So that when the post is saved, the CMS knows what permalink structure to use
        $custom_types = $this->Config->get('cms.custom_posts');
        $custom_types[$type] = str_replace(['(', ':', ')'], '', $route);

        $this->Config->set('cms.custom_posts', $custom_types);

        // Add the menu to the sidebar
        $this->Filters->on('adminSidebar', function($sidebar) use ($title, $slug, $icon)
        {
            $sidebar['content']['children'] = Arr::insertAt($sidebar['content']['children'] ,
                ["$slug" =>
                    [
                        'link'     => '/admin/' . $slug . '/',
                        'text'     => Humanizer::pluralize(ucfirst($title)),
                        'icon'     => $icon,
                        'children' => [],
                    ],
                ],
            2);

            return $sidebar;
        });

        if ($isPage)
        {
            // Filter the page title
            $this->Filters->on('adminPageTitle', function($_title) use ($title, $isPage)
            {
                return Humanizer::pluralize(ucfirst($title)) . ' | Serve';
            });
            // Add the custom post type to the model
            $this->Filters->on('adminCustomPostType', function() use ($isPage, $slug)
            {
                return $slug;
            });
            // Filter the request name
            $this->Filters->on('adminRequestName', function($requestName) use ($slug, $isPage)
            {
                return $slug;
            });
        }

        // Add the custom post type to the dropdown in
        // The admin panel
        $this->Filters->on('adminPostTypes', function($types) use ($title, $slug)
        {
            $types[$title] = $slug;

            return $types;
        });
    }

    /**
     * Adds a custom page to the Admin Panel.
     *
     * @param string      $title     The page title
     * @param string      $slug      The page slug
     * @param string      $icon      The icon in the sidebar to use
     * @param string      $model     The model to use for loading
     * @param string      $view      Absolute file path to include for page content
     * @param string|null $parent    Parent page slug (optional) (default null)
     * @param bool        $adminOnly Allow only administrators to use this page
     * @param array|null  $styles    Any custom styles to add into the page <head> (optional) (default null)
     * @param array|null  $scripts   Anything to go before the closing <body> tag (optional) (default null)
     */
    public function addPage(string $title, string $slug, string $icon, string $model, string $view, string $parent = null, bool $adminOnly = false, array $styles = null, array $scripts = null)
    {
        if ($this->Application->isCommandLine())
        {
            return false;
        }

        if (!$this->Gatekeeper->isLoggedIn() || !$this->Gatekeeper->getUser())
        {
            return false;
        }

        if ($this->Gatekeeper->getUser()->role !== 'administrator' && $adminOnly === true)
        {
            return false;
        }

        if ($parent)
        {
            $slug = $parent . '/' . $slug;
        }

        // Add the route only if the current user is logged as admin
        $this->Router->get("/admin/{$slug}/",        DashController::class . '@blankPage', $model);
        $this->Router->get("/admin/{$slug}/(:all)",  DashController::class . '@blankPage', $model);
        $this->Router->post("/admin/{$slug}/",       DashController::class . '@blankPage', $model);
        $this->Router->post("/admin/{$slug}/(:all)", DashController::class . '@blankPage', $model);

        // If this is a child menu item is this page being requested ?
        if ($parent)
        {
            $requestSlug = explode('/', $this->Request->environment()->REQUEST_PATH);
            array_shift($requestSlug);
            $requestSlug = implode('/', $requestSlug);
            $isPage      = $slug === $requestSlug;
        }
        else
        {
            // Is this page being requested ?
            $requestSlug = Str::getAfterLastChar($this->Request->environment()->REQUEST_PATH, '/');
            $isPage      = $slug === $requestSlug;
        }

        // Add the menu to the sidebar
        $this->Filters->on('adminSidebar', function($sidebar) use ($title, $slug, $icon, $parent)
        {
            if ($parent)
            {
                foreach ($sidebar as $name => $item)
                {
                    if ($name === $parent)
                    {
                        $sidebar[$name]['children'][$slug] =
                        [
                            'link'     => '/admin/' . $slug . '/',
                            'text'     => $title,
                            'icon'     => $icon,
                        ];
                    }
                }

                return $sidebar;
            }

            return Arr::insertAt($sidebar,
                ["$slug" =>
                    [
                        'link'     => '/admin/' . $slug . '/',
                        'text'     => $title,
                        'icon'     => $icon,
                        'children' => [],
                    ],
                ],
            8);
        });

        // Filter the request name
        $this->Filters->on('adminRequestName', function($requestName) use ($slug, $isPage)
        {
            if ($isPage)
            {
                return $slug;
            }

            return $requestName;
        });

        // Filter the title
        $this->Filters->on('adminPageTitle', function($_title) use ($title, $isPage)
        {
            if ($isPage)
            {
                return ucfirst($title) . ' | Serve';
            }

            return $_title;
        });

        // Filter the admin page to include
        $this->Filters->on('adminPageTemplate', function($requestName) use ($isPage, $view)
        {
            if ($isPage)
            {
                return $view;
            }

            return $requestName;
        });

        // Add stylesheets and JS scripts to admin panel
        if ($styles && $isPage)
        {
            $this->Filters->on('adminHeaderScripts', function($CSS) use ($styles)
            {
                $CSS = array_merge($CSS, $styles);

                return $CSS;
            });
        }

        // Add stylesheets and JS scripts to admin panel
        if ($scripts && $isPage)
        {
            $this->Filters->on('adminHeaderScripts', function($JS) use ($scripts)
            {
                $JS[] = array_merge($JS, $scripts);

                return $JS;
            });
        }
    }
}
