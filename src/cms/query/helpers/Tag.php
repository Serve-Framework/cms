<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\query\helpers;

/**
 * CMS Query tag methods.
 *
 * @author Joe J. Howard
 */
class Tag extends Helper
{
    /**
     * Checks whether a given tag exists by the tag name or id.
     *
     * @param  string|int $tag_name Tag name or id
     * @return bool
     */
    public function tag_exists($tag_name)
    {
        $index = is_numeric($tag_name) ? 'id' : 'name';

        $tag_name = is_numeric($tag_name) ? intval($tag_name) : $tag_name;

        return !empty($this->container->TagManager->provider()->byKey($index, $tag_name));
    }

    /**
     * Gets an array of tag objects of the current post or a post by id.
     *
     * @param  int|null $post_id Post id or null for tags of current post (optional) (Default NULL)
     * @return array
     */
    public function the_tags(int $post_id = null)
    {
        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post)
            {
                return $post->tags;
            }
        }
        elseif (!empty($this->query->post))
        {
            return $this->query->post->tags;
        }

        return [];
    }

    /**
     * Get a comma separated list of the tag names of the current post or a post by id.
     *
     * @param  int|null $post_id Post id or null for tags of current post (optional) (Default NULL)
     * @param  string   $glue    Glue to separate tag names
     * @return string
     */
    public function the_tags_list(int $post_id = null, string $glue = ', '): string
    {
        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post)
            {
                return $this->listTags($post->tags, $glue);
            }
        }
        elseif (!empty($this->query->post))
        {
            return $this->listTags($this->query->post->tags, $glue);
        }

        return '';
    }

    /**
     * Implode tag names.
     *
     * @param  array  $tags Array of tag objects
     * @param  string $glue Glue to separate tag names
     * @return string
     */
    private function listTags(array $tags, string $glue): string
    {
        $str = '';

        foreach ($tags as $tag)
        {
            $str .= $tag->name . $glue;
        }

        $split = array_filter(explode($glue, $str));

        return implode($glue, $split);
    }

    /**
     * Get the slug of a tag by id or the current post's tag.
     *
     * @param  int|null    $tag_id Tag id or null for tag of current post (optional) (Default NULL)
     * @return string|null
     */
    public function the_tag_slug(int $tag_id = null)
    {
        $tag = false;

        if (!$tag_id)
        {
            if (!empty($this->query->post))
            {
                $tag = $this->query->post->tags[0];
            }
        }
        else
        {
            $tag = $this->query->helper('cache')->getTagById($tag_id);
        }

        if ($tag)
        {
            return $tag->slug;
        }

        return null;
    }

    /**
     * Get the full URL of a tag by id or current post's tag.
     *
     * @param  int|null    $tag_id Tag id or null for tag of current post (optional) (Default NULL)
     * @return string|null
     */
    public function the_tag_url(int $tag_id = null)
    {
        $tag = false;

        if (!$tag_id)
        {
            if (!empty($this->query->post))
            {
                $tag = $this->query->post->tags[0];
            }
        }
        else
        {
            $tag = $this->query->helper('cache')->getTagById($tag_id);
        }

        if ($tag)
        {
            $prefix = !empty($this->query->blog_location()) ? '/' . $this->query->blog_location() . '/' : '/';

            return $this->container->Request->environment()->HTTP_HOST . $prefix . 'tag/' . $tag->slug . '/';
        }

        return null;
    }

    /**
     * If the request is for a tag, category or author returns the object of that request.
     *
     * @return mixed
     */
    public function the_taxonomy()
    {
        $key = $this->query->helper('cache')->key(__FUNCTION__, func_get_args(), func_num_args());

        if ($this->query->helper('cache')->has($key))
        {
            return $this->query->helper('cache')->get($key);
        }

        if ($this->query->requestType === 'category')
        {
            return $this->query->helper('cache')->set($key, $this->container->CategoryManager->provider()->byKey('slug', $this->query->taxonomySlug, true));
        }
        elseif ($this->query->requestType === 'tag')
        {
            return $this->query->helper('cache')->set($key, $this->container->TagManager->provider()->byKey('slug', $this->query->taxonomySlug, true));
        }
        elseif ($this->query->requestType === 'author')
        {
            return $this->query->helper('cache')->set($key, $this->container->UserManager->provider()->byKey('slug', $this->query->taxonomySlug, true));
        }

        return null;
    }

    /**
     * Get an array of all the tag objects.
     *
     * @return array
     */
    public function all_the_tags(): array
    {
        $key = $this->query->helper('cache')->key(__FUNCTION__, func_get_args(), func_num_args());

        if ($this->query->helper('cache')->has($key))
        {
            return $this->query->helper('cache')->get($key);
        }

        $tags = [];

        $rows = $this->sql()->SELECT('id')->FROM('tags')->ORDER_BY('name', 'ASC')->FIND_ALL();

        if ($rows)
        {
            foreach ($rows as $row)
            {
                $tags[] = $this->container->TagManager->byId($row['id']);
            }
        }

        return $this->query->helper('cache')->set($key, $tags);
    }

    /**
     * Is the current post or a post by id untagged ?
     *
     * @param  int|null $post_id Post id or null for tag of current post (optional) (Default NULL)
     * @return bool
     */
    public function has_tags(int $post_id = null)
    {
        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post)
            {
                $tags = $post->tags;

                if (count($tags) === 1)
                {
                    if ($tags[0]->id === 1) return false;
                }

                return true;
            }

            return false;
        }

        if (!empty($this->query->post))
        {
            $tags = $this->query->post->tags;

            if (count($tags) === 1)
            {
                if ($tags[0]->id === 1) return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Ge an array of Post objects by tag id.
     *
     * @param  int   $tag_id    The Tag id
     * @param  bool  $published Get only published articles (optional) (Default TRUE)
     * @return array
     */
    public function the_tag_posts(int $tag_id, bool $published = true): array
    {
        $key = $this->query->helper('cache')->key(__FUNCTION__, func_get_args(), func_num_args());

        if ($this->query->helper('cache')->has($key))
        {
            return $this->query->helper('cache')->get($key);
        }

        if ($this->query->tag_exists($tag_id))
        {
            return $this->query->helper('cache')->set($key, $this->container->PostManager->provider()->byKey('tags.id', $tag_id, false, $published));
        }

        return $this->query->helper('cache')->set($key, []);
    }
}
