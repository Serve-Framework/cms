<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\query\helpers;

use serve\utility\Str;

/**
 * CMS Query meta methods.
 *
 * @author Joe J. Howard
 */
class Meta extends Helper
{
    /**
     * Get the website title from the config.
     *
     * @return string
     */
    public function website_title(): string
    {
        return $this->container->Config->get('cms.site_title');
    }

    /**
     * Get the website description from the config.
     *
     * @return string
     */
    public function website_description(): string
    {
        return $this->container->Config->get('cms.site_description');
    }

    /**
     * Get the website's domain name (e.g "example.com").
     *
     * @return string
     */
    public function domain_name(): string
    {
        return $this->container->Request->environment()->DOMAIN_NAME;
    }

    /**
     * Get the meta description to display in the website's head.
     *
     * @return string
     */
    public function the_meta_description(): string
    {
        if ($this->query->is_not_found())
        {
            return 'The page you are looking for could not be found.';
        }

        $description = $this->query->website_description();

        if ($this->query->is_single() || $this->query->is_page() || $this->query->is_custom_post())
        {
            if (isset($this->query->the_post_meta()['meta_description']))
            {
                $description = $this->query->the_post_meta()['meta_description'];
            }
            elseif ($this->query->have_posts())
            {
                $description = $this->query->post->excerpt;
            }
        }
        elseif ($this->query->is_tag() || $this->query->is_category() || $this->query->is_author())
        {
            $description = $this->query->the_taxonomy()->description;
        }
        elseif ($this->query->is_search())
        {
            $description = 'Search Results for: ' . $this->query->search_query() . ' - ' . $this->query->website_title();
        }

        if (!$description)
        {
            $description = '';
        }

        return Str::reduce($description, 300);
    }

    /**
     * Get the meta title to display in the website's head.
     *
     * @return string
     */
    public function the_meta_title(): string
    {
        $uri        = explode('/', $this->container->Request->environment()->REQUEST_PATH);
        $titleBase  = $this->query->website_title();
        $titlePage  = $this->query->pageIndex > 1 ? 'Page ' . ($this->query->pageIndex) . ' | ' : '';
        $titleTitle = '';

        if ($this->query->is_not_found())
        {
            return 'Page Not Found';
        }

        if ($this->query->is_single() || $this->query->is_page() || $this->query->is_custom_post())
        {
            if (isset($this->query->the_post_meta()['meta_title']))
            {
                $titleTitle = $this->query->the_post_meta()['meta_title'] . ' | ';
            }
            elseif ($this->query->have_posts())
            {
                $titleTitle = $this->query->post->title . ' | ';
            }
        }
        elseif ($this->query->is_tag() || $this->query->is_category() || $this->query->is_author())
        {
            $titleTitle = $this->query->the_taxonomy()->name . ' | ';
        }
        elseif ($this->query->is_search())
        {
            $titleTitle = 'Search Results | ';
        }

        return  $titleTitle . $titlePage . $titleBase;
    }

    /**
     * Get the canonical URL to display in the website's head.
     *
     * @return string
     */
    public function the_canonical_url(): string
    {
        $urlParts = array_filter(explode('/', $this->container->Request->environment()->REQUEST_PATH));
        $last     = isset($urlParts[0]) ? array_values(array_slice($urlParts, -1))[0] : false;

        if (!$last || is_home())
        {
            return $this->query->home_url();
        }

        if ($last === 'rss' || $last === 'rdf' || $last == 'atom')
        {
            array_pop($urlParts);
            array_pop($urlParts);
        }
        elseif ($last === 'feed')
        {
            array_pop($urlParts);
        }

        return $this->container->Request->environment()->HTTP_HOST . '/' . implode('/', $urlParts) . '/';
    }

    /**
     * Get the title of the next page or post.
     * Works on single, home, author, tag, category requests.
     *
     * @return string|false
     */
    public function the_previous_page_title()
    {
        $prev_page = $this->query->the_previous_page();

        if ($prev_page && isset($prev_page['title']))
        {
            return $prev_page['title'];
        }

        return false;
    }

    /**
     * Get the title of the next page or post.
     * Works on single, home, author, tag, category requests.
     *
     * @return string|false
     */
    public function the_next_page_title()
    {
        $next_page = $this->query->the_next_page();

        if ($next_page && isset($next_page['title']))
        {
            return $next_page['title'];

        }

        return false;
    }

    /**
     * Get the full URL of the next page or post.
     * Works on single, home, author, tag, category requests.
     *
     * @return string|false
     */
    public function the_next_page_url()
    {
        $next_page = $this->query->the_next_page();

        if ($next_page && isset($next_page['slug']))
        {
            return $this->container->Request->environment()->HTTP_HOST . '/' . $next_page['slug'];
        }

        return false;
    }

    /**
     * Get the full URL of the previous page or post.
     * Works on single, home, author, tag, category requests.
     *
     * @return string|false
     */
    public function the_previous_page_url()
    {
        $prev_page = $this->query->the_previous_page();

        if ($prev_page && isset($prev_page['slug']))
        {
            return $this->container->Request->environment()->HTTP_HOST . '/' . $prev_page['slug'];
        }

        return false;
    }

    /**
     * Gets an array for the previous page or post returning its title and slug.
     * Works on single, home, author, tag, category requests.
     *
     * @return array|false
     */
    public function the_previous_page()
    {
        // Not found don't bother
        if ($this->query->is_not_found())
        {
            return false;
        }

        // Get from the cache
        $key = $this->query->helper('cache')->key(__FUNCTION__, func_get_args(), func_num_args());

        // There are only next/prev pages for single, tags, category, author, and homepage
        if (!in_array($this->query->requestType, ['single', 'home', 'home-page', 'tag', 'category', 'author']) && !$this->query->is_custom_post())
        {
            return $this->query->helper('cache')->set($key, false);
        }

        // Load from cache if we can
        if ($this->query->helper('cache')->has($key))
        {
            return $this->query->helper('cache')->get($key);
        }

        // If this is a single or custom post just find the next post
        if ($this->query->is_single() || $this->query->is_custom_post())
        {
            return $this->query->helper('cache')->set($key, $this->findPrevPost($this->query->post));
        }

        // This must now be a paginated page - tag, category, author or homepage listing
        // Get the current page + posts per page and check if there is a page before that
        if ($this->query->pageIndex > 1)
        {
            $queryFilter = $this->query->queryFilter;
            
            $queryFilter['page'] = $queryFilter['page'] - 1;

            $query = $this->query->create($queryFilter, $this->query->requestType);

            $posts = $query->posts;
        }
        else
        {
            $posts = [];
        }

        if (!empty($posts))
        {
            $prevpage   = $this->query->pageIndex;
            $uri        = explode('/', $this->container->Request->environment()->REQUEST_PATH);

            $titleBase  = $this->query->website_title();
            $titlePage  = $prevpage > 1 ? 'Page ' . $prevpage . ' | ' : '';
            $titleTitle = '';
            $slug       = '';
            $base       = !empty($this->query->blog_location()) && ($this->query->is_tag() || $this->query->is_category() || $this->query->is_author() || $this->query->is_home() || $this->query->is_blog_location()) ? $this->query->blog_location() . '/' : '';

            if ($this->query->is_home())
            {
                if (!empty($this->query->blog_location()))
                {
                    return false;
                }

                $slug = $prevpage > 1 ? 'page/' . $prevpage . '/' : '';
            }
            elseif ($this->query->is_blog_location())
            {
                $titleTitle = 'Blog | ';
                $slug       = $prevpage > 1 ? $base . 'page/' . $prevpage . '/' : $base;
            }
            elseif ($this->query->is_tag() || $this->query->is_category() || $this->query->is_author())
            {
                $taxonomy   = $this->query->is_tag() ? 'tag' : 'author';
                $taxonomy   = $this->query->is_category() ? 'category' : $taxonomy;
                $titleTitle = $this->query->the_taxonomy()->name . ' | ';
                $slug       = $prevpage > 1 ? $base . $taxonomy . '/' . $this->query->taxonomySlug . '/page/' . $prevpage . '/' : $base . $taxonomy . '/' . $this->query->taxonomySlug . '/';
            }
            elseif ($this->query->is_search())
            {
                $titleTitle = 'Search Results | ';
                $slug       =  $prevpage > 1 ? $uri[0] . '/' . $uri[1] . '/page/' . $prevpage . '/' : $uri[0] . '/' . $uri[1] . '/';
            }

            return $this->query->helper('cache')->set($key, [
                'title' => $titleTitle . $titlePage . $titleBase,
                'slug'  => $slug,
            ]);
        }

        return $this->query->helper('cache')->set($key, false);
    }

    /**
     * Gets an array for the next page returning its title and slug.
     * Works on single, home, author, tag, category requests.
     *
     * @return array|false
     */
    public function the_next_page()
    {
        // Not found don't bother
        if ($this->query->is_not_found())
        {
            return false;
        }

        // Get for the cache
        $key = $this->query->helper('cache')->key(__FUNCTION__, func_get_args(), func_num_args());

        // There are only next/prev pages for single, tags, category, author, and homepage
        if (!in_array($this->query->requestType, ['single', 'home', 'home-page', 'tag', 'category', 'author']) && !$this->query->is_custom_post())
        {
            return $this->query->helper('cache')->set($key, false);
        }

        // Load from cache if we can
        if ($this->query->helper('cache')->has($key))
        {
            return $this->query->helper('cache')->get($key);
        }

        // If this is a single or custom post just find the next post
        if ($this->query->is_single() || $this->query->is_custom_post())
        {
            return $this->query->helper('cache')->set($key, $this->findNextPost($this->query->post));
        }

        $queryFilter = $this->query->queryFilter;
            
        $queryFilter['page'] = $queryFilter['page'] + 1;

        $query = $this->query->create($queryFilter, $this->query->requestType);

        $posts = $query->posts;

        $base  = !empty($this->query->blog_location()) && ($this->query->is_tag() || $this->query->is_category() || $this->query->is_author() || $this->query->is_home() || $this->query->is_blog_location()) ? $this->query->blog_location() . '/' : '';

        if (!empty($posts))
        {
            $nextPage   = $this->query->pageIndex + 1;
            $uri        = explode('/', $this->container->Request->environment()->REQUEST_PATH);
            $titleBase  = $this->query->website_title();
            $titlePage  = $nextPage > 1 ? 'Page ' . $nextPage . ' | ' : '';
            $titleTitle = '';
            $slug       = '';

            if ($this->query->is_home())
            {
                if (!empty($this->query->blog_location()))
                {
                    return false;
                }

                $slug = 'page/' . $nextPage . '/';
            }
            elseif ($this->query->is_blog_location())
            {
                $titleTitle = 'Blog | ';
                $slug       = $base . 'page/' . $nextPage . '/';
            }
            elseif ($this->query->is_tag() || $this->query->is_category() || $this->query->is_author())
            {
                $taxonomy   = $this->query->is_tag() ? 'tag' : 'author';
                $taxonomy   = $this->query->is_category() ? 'category' : $taxonomy;
                $titleTitle = $this->query->the_taxonomy()->name . ' | ';
                $titleTitle = $this->query->the_taxonomy()->name . ' | ';
                $slug       = $base . $taxonomy . '/' . $this->query->taxonomySlug . '/page/' . $nextPage . '/';
            }
            elseif ($this->query->is_search())
            {
                $titleTitle = 'Search Results | ';
                $slug       = $uri[0] . '/' . $uri[1] . '/page/' . $nextPage . '/';
            }
            return $this->query->helper('cache')->set($key, [
                'title' => $titleTitle . $titlePage . $titleBase,
                'slug'  => $slug,
            ]);
        }

        return $this->query->helper('cache')->set($key, false);
    }

    /**
     * Find the next post (used internally).
     *
     * @param  \cms\wrappers\Post|null $post Current post (if it exists)
     * @return array|false
     */
    private function findNextPost($post)
    {
        if (!$post)
        {
            return false;
        }

        $next = $this->sql()->SELECT('id')->FROM('posts')->WHERE('created', '>=', $post->created)->AND_WHERE('type', '=', $post->type)->AND_WHERE('status', '=', 'published')->ORDER_BY('created', 'ASC')->FIND_ALL();

        if (!empty($next))
        {
            $next = array_values($next);

            foreach ($next as $i => $prevPost)
            {
                if ((int) $prevPost['id'] === (int) $post->id)
                {
                    if (isset($next[$i+1]))
                    {
                        return $this->sql()->SELECT('*')->FROM('posts')->WHERE('id', '=', $next[$i+1]['id'])->AND_WHERE('type', '=', $post->type)->ROW();
                    }
                }
            }
        }

        return false;
    }

    /**
     * Find the previous post (used internally).
     *
     * @param  \cms\wrappers\Post|null $post Current post (if it exists)
     * @return array|false
     */
    private function findPrevPost($post)
    {
        if (!$post)
        {
            return false;
        }

        $next = $this->sql()->SELECT('id')->FROM('posts')->WHERE('created', '<=', $post->created)->AND_WHERE('type', '=', $post->type)->AND_WHERE('status', '=', 'published')->ORDER_BY('created', 'DESC')->FIND_ALL();

        if (!empty($next))
        {
            $next = array_values($next);

            foreach ($next as $i => $prevPost)
            {
                if ((int) $prevPost['id'] === (int) $post->id)
                {
                    if (isset($next[$i+1]))
                    {
                        return $this->sql()->SELECT('*')->FROM('posts')->WHERE('id', '=', $next[$i+1]['id'])->AND_WHERE('type', '=', $post->type)->ROW();
                    }
                }
            }
        }

        return false;
    }
}
