<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\query\helpers;

/**
 * CMS Query category methods.
 *
 * @author Joe J. Howard
 */
class Category extends Helper
{
    /**
     * Checks whether a given category exists by the category name or id.
     *
     * @param  string|int $category_name Category name or id
     * @return bool
     */
    public function category_exists($category_name): bool
    {
        $index = is_numeric($category_name) ? 'id' : 'name';

        $category_name = is_numeric($category_name) ? intval($category_name) : $category_name;

        return !empty($this->container->CategoryManager->provider()->byKey($index, $category_name));
    }

    /**
     * Gets the first category of the current post or a post by id.
     *
     * @param  int|null                          $post_id Post id or null for category of current post (optional) (Default NULL)
     * @return \cms\wrappers\Category|null
     */
    public function the_category(int $post_id = null)
    {
        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post)
            {
                return $post->categories[0];
            }
        }

        elseif (!empty($this->query->post))
        {
            return $this->query->post->categories[0];
        }

        return null;
    }

    /**
     * Gets the first category name of the current post or a post by id.
     *
     * @param  int|null    $post_id Post id or null for category of current post (optional) (Default NULL)
     * @return string|null
     */
    public function the_category_name(int $post_id = null)
    {
        $category = $this->query->the_category($post_id);

        if ($category)
        {
            return $category->name;
        }

        return null;
    }

    /**
     * Get an array of categories of the current post or a post by id.
     *
     * @param  int|null $post_id Post id or null for category of current post (optional) (Default NULL)
     * @return array
     */
    public function the_categories(int $post_id = null): array
    {
        $categories = [];

        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post)
            {
                $categories = $post->categories;
            }
        }

        elseif (!empty($this->query->post))
        {
            $categories = $this->query->post->categories;
        }

        if ($categories && isset($categories[0]))
        {
            $flattened = [];
            $category  = $categories[0];
            $parent    = $category->parent();

            if ($parent)
            {
                $flattened[] = $category;

                while ($parent)
                {
                    $flattened[] = $parent;
                    $parent      = $parent->parent();
                }

                $flattened = array_reverse($flattened);

                return $flattened;
            }
        }

        return $categories;
    }

    /**
     * Get a comma separated list of the category names of the current post or a post by id.
     *
     * @param  int|null $post_id Post id or null for category of current post (optional) (Default NULL)
     * @param  string   $glue    Glue to separate category names
     * @return string
     */
    public function the_categories_list(int $post_id = null, string $glue = ', '): string
    {
        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post)
            {
                return $this->listCategories($this->query->the_categories($post->id), $glue);
            }
        }
        elseif (!empty($this->query->post))
        {
            return $this->listCategories($this->query->the_categories($this->query->post->id), $glue);
        }

        return '';
    }

    /**
     * Implode category names.
     *
     * @param  array  $categories Array of category objects
     * @param  string $glue       Glue to separate category names
     * @return string
     */
    private function listCategories(array $categories, string $glue): string
    {
        $str = '';

        foreach ($categories as $category)
        {
            $str .= $category->name . $glue;
        }

        $split = array_filter(explode($glue, $str));

        return implode($glue, $split);
    }

    /**
     * Get the slug of a category by id or the current post's first category.
     *
     * @param  int|null    $category_id Category id or null for category of current post (optional) (Default NULL)
     * @return string|null
     */
    public function the_category_slug(int $category_id = null)
    {
        $category = false;

        if (!$category_id)
        {
            if (!empty($this->query->post))
            {
                $category = $this->query->post->category;
            }
        }
        else
        {
            $category = $this->query->helper('cache')->getCategoryById($category_id);
        }

        if ($category)
        {
            $slugs  = [];
            $parent = $category->parent();

            if ($parent)
            {
                $slugs[] = $category->slug;

                while ($parent)
                {
                    $slugs[] = $parent->slug;
                    $parent  = $parent->parent();
                }

                $slugs = array_reverse($slugs);

                return trim(implode('/', $slugs), '/');
            }

            return $category->slug;
        }

        return null;
    }

    /**
     * Get the full URL of a category by id or current post's first category.
     *
     * @param  int|null    $category_id Category id or null for category of current post (optional) (Default NULL)
     * @return string|null
     */
    public function the_category_url(int $category_id = null)
    {
        $slug = $this->query->the_category_slug($category_id);

        if ($slug)
        {
            $prefix = !empty($this->query->blog_location()) ? '/' . $this->query->blog_location() . '/' : '/';

            return $this->container->Request->environment()->HTTP_HOST . $prefix . 'category/' . $slug . '/';
        }

        return null;
    }

    /**
     * Get an array of all the Category objects.
     *
     * @return array
     */
    public function all_the_categories(): array
    {
        $key = $this->query->helper('cache')->key(__FUNCTION__, func_get_args(), func_num_args());

        if ($this->query->helper('cache')->has($key))
        {
            return $this->query->helper('cache')->get($key);
        }

        $categories = [];

        $rows = $this->sql()->SELECT('id')->FROM('categories')->ORDER_BY('name', 'ASC')->FIND_ALL();

        foreach ($rows as $row)
        {
            $categories[] = $this->container->CategoryManager->byId($row['id']);
        }

        return $this->query->helper('cache')->set($key, $categories);
    }

    /**
     * Is the current post or a post by id uncategorized ?
     *
     * @param  int|null $post_id Post id or null for category of current post (optional) (Default NULL)
     * @return bool
     */
    public function has_categories(int $post_id = null): bool
    {
        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post)
            {
                $categories = $post->categories;

                if (count($categories) === 1)
                {
                    if ($categories[0]->id === 1) return false;
                }

                return true;
            }

            return false;
        }

        if (!empty($this->query->post))
        {
            $categories = $this->query->post->categories;

            if (count($categories) === 1)
            {
                if ($categories[0]->id === 1) return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Ge an array of Post objects by category id.
     *
     * @param  int   $category_id The category id
     * @param  bool  $published   Get only published articles (optional) (Default TRUE)
     * @return array
     */
    public function the_category_posts(int $category_id, bool $published = true): array
    {
        $key = $this->query->helper('cache')->key(__FUNCTION__, func_get_args(), func_num_args());

        if ($this->query->helper('cache')->has($key))
        {
            return $this->query->helper('cache')->get($key);
        }

        if ($this->query->category_exists($category_id))
        {
            return $this->query->helper('cache')->set($key, $this->query->create('post_status = published : category_id = ' . $category_id)->the_posts());
        }

        return $this->query->helper('cache')->set($key, []);
    }
}
