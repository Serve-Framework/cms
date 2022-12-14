<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\query\helpers;

use serve\utility\Str;

/**
 * CMS Query author methods.
 *
 * @author Joe J. Howard
 */
class Author extends Helper
{
    /**
     * Get the author of the current post or a post by id.
     *
     * @param  int|null                      $post_id Post id (optional) (Default NULL)
     * @return \cms\wrappers\User|null
     */
    public function the_author(int $post_id = null)
    {
        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post)
            {
                return $this->query->helper('cache')->getAuthorById($post->author_id);
            }

            return null;
        }

        if (!empty($this->query->post))
        {
            return $this->query->helper('cache')->getAuthorById($this->query->post->author_id);
        }

        return null;
    }

    /**
     * Checks whether a given author exists by name or id.
     *
     * @param  string|int $usernameOrId Username or id
     * @return bool
     */
    public function author_exists($usernameOrId): bool
    {
        $index = is_numeric($usernameOrId) ? 'id' : 'username';

        $usernameOrId = is_numeric($usernameOrId) ? intval($usernameOrId) : $usernameOrId;

        $author = $this->container->UserManager->provider()->byKey($index, $usernameOrId, true);

        if ($author)
        {
            return $author->role === 'administrator' || $author->role === 'writer';
        }

        return false;
    }

    /**
     * Does the author of the current post or an author by id have a thumbnail attachment.
     *
     * @param  int|null $author_id Author id or null for author of current post (optional) (Default NULL)
     * @return bool
     */
    public function has_author_thumbnail($author_id = null): bool
    {
        if ($author_id)
        {
            $author = $this->query->helper('cache')->getAuthorById($author_id);

            if ($author)
            {
                return !empty($this->query->helper('cache')->getMediaById($author->thumbnail_id));
            }

            return false;
        }

        if (!empty($this->query->post))
        {
            $author = $this->query->helper('cache')->getAuthorById($this->query->post->author_id);

            if ($author)
            {
                return !empty($this->query->helper('cache')->getMediaById($author->thumbnail_id));
            }
        }

        return false;
    }

    /**
     * Get the author name of the current post or an author by id.
     *
     * @param  int|null    $author_id Author id or null for author of current post (optional) (default NULL)
     * @return string|null
     */
    public function the_author_name(int $author_id = null)
    {
        if ($author_id)
        {
            $author = $this->query->helper('cache')->getAuthorById($author_id);

            if ($author)
            {
                return $author->name;
            }

            return null;
        }

        if (!empty($this->query->post))
        {
            $author = $this->query->helper('cache')->getAuthorById($this->query->post->author_id);

            if ($author)
            {
                return $author->name;
            }
        }

        return null;
    }

    /**
     * Get the author's full URL of the current post or an author by id.
     *
     * @param  int|null    $author_id Author id or null for author of current post (optional) (default NULL)
     * @return string|null
     */
    public function the_author_url(int $author_id = null)
    {
        if ($author_id)
        {
            $author = $this->query->helper('cache')->getAuthorById($author_id);

            if ($author)
            {
                $prefix = !empty($this->query->blog_location()) ? '/' . $this->query->blog_location() . '/' : '/';

                return $this->container->Request->environment()->HTTP_HOST . $prefix . 'author/' . $author->slug . '/';
            }

            return null;
        }

        if (!empty($this->query->post))
        {
            $author = $this->query->helper('cache')->getAuthorById($this->query->post->author_id);

            if ($author)
            {
                $prefix = !empty($this->query->blog_location()) ? '/' . $this->query->blog_location() . '/' : '/';

                return $this->container->Request->environment()->HTTP_HOST . $prefix . 'author/' . $author->slug . '/';
            }
        }

        return null;
    }

    /**
     * Get the authors thumbnail attachment of the current post or an author by id.
     *
     * @param  int|null                       $author_id Author id or null for author of current post (optional) (default NULL)
     * @return \cms\wrappers\Media|null
     */
    public function the_author_thumbnail(int $author_id = null)
    {
        if ($author_id)
        {
            $author = $this->query->helper('cache')->getAuthorById($author_id);

            if ($author && $this->query->has_author_thumbnail($author_id))
            {
                return $this->query->helper('cache')->getMediaById($author->thumbnail_id);
            }

            return null;
        }

        if (!empty($this->query->post))
        {
            $author = $this->query->helper('cache')->getAuthorById($this->query->post->author_id);

            if ($author && $this->query->has_author_thumbnail($author->id))
            {
                return $this->query->helper('cache')->getMediaById($author->thumbnail_id);
            }

            return null;
        }

        return null;
    }

    /**
     * Get the authors bio of the current post or an author by id.
     *
     * @param  int|null    $author_id Author id or null for author of current post (optional) (default NULL)
     * @return string|null
     */
    public function the_author_bio(int $author_id = null)
    {
        if ($author_id)
        {
            $author = $this->query->helper('cache')->getAuthorById($author_id);

            if ($author)
            {
                return Str::nl2br($author->description);
            }

            return null;
        }

        if (!empty($this->query->post))
        {
            $author = $this->query->helper('cache')->getAuthorById($this->query->post->author_id);

            if ($author)
            {
                return Str::nl2br($author->description);
            }

        }

        return null;
    }

    /**
     * Get the authors twitter URL of the current post or an author by id.
     *
     * @param  int|null    $author_id Author id or null for author of current post (optional) (default NULL)
     * @return string|null
     */
    public function the_author_twitter(int $author_id = null)
    {
        if ($author_id)
        {
            $author = $this->query->helper('cache')->getAuthorById($author_id);

            if ($author)
            {
                return $author->twitter;
            }

            return null;
        }

        if (!empty($this->query->post))
        {
            $author = $this->query->helper('cache')->getAuthorById($this->query->post->author_id);

            if ($author)
            {
                return $author->twitter;
            }

        }

        return null;
    }

    /**
     * Get the authors google URL of the current post or an author by id.
     *
     * @param  int|null    $author_id Author id or null for author of current post (optional) (default NULL)
     * @return string|null
     */
    public function the_author_google(int $author_id = null)
    {
        if ($author_id)
        {
            $author = $this->query->helper('cache')->getAuthorById($author_id);

            if ($author)
            {
                return $author->gplus;
            }

            return null;
        }

        if (!empty($this->query->post))
        {
            $author = $this->query->helper('cache')->getAuthorById($this->query->post->author_id);

            if ($author)
            {
                return $author->gplus;
            }

        }

        return null;
    }

    /**
     * Get the authors facebook URL of the current post or an author by id.
     *
     * @param  int|null    $author_id Author id or null for author of current post (optional) (default NULL)
     * @return string|null
     */
    public function the_author_facebook(int $author_id = null)
    {
        if ($author_id)
        {
            $author = $this->query->helper('cache')->getAuthorById($author_id);

            if ($author)
            {
                return $author->facebook;
            }

            return null;
        }

        if (!empty($this->query->post))
        {
            $author = $this->query->helper('cache')->getAuthorById($this->query->post->author_id);

            if ($author)
            {
                return $author->facebook;
            }
        }

        return null;
    }

    /**
     * Get the authors instagram URL of the current post or an author by id.
     *
     * @param  int|null    $author_id Author id or null for author of current post (optional) (default NULL)
     * @return string|null
     */
    public function the_author_instagram(int $author_id = null)
    {
        if ($author_id)
        {
            $author = $this->query->helper('cache')->getAuthorById($author_id);

            if ($author)
            {
                return $author->instagram;
            }

            return null;
        }

        if (!empty($this->query->post))
        {
            $author = $this->query->helper('cache')->getAuthorById($this->query->post->author_id);
            if ($author)
            {
                return $author->instagram;
            }
        }

        return null;
    }

    /**
     * Get an array of user object of all authors.
     *
     * @return array
     */
    public function all_the_authors(): array
    {
        $key = $this->query->helper('cache')->key(__FUNCTION__, func_get_args(), func_num_args());

        if ($this->query->helper('cache')->has($key))
        {
            return $this->query->helper('cache')->get($key);
        }

        $authors = [];

        $rows = $this->sql()->SELECT('id, role')->FROM('users')->ORDER_BY('name', 'ASC')->FIND_ALL();

        if ($rows)
        {
            
        }
        foreach ($rows as $row)
        {
            if ($row['role'] !== 'administrator' && $row['role'] !== 'writer')
            {
                continue;
            }

            $authors[] = $this->container->UserManager->provider()->byId($row['id']);
        }

        return $this->query->helper('cache')->set($key, $authors);
    }

    /**
     * Ge an array of Post objects objects by author id.
     *
     * @param  int   $author_id The author id
     * @param  bool  $published Get only published articles (optional) (Default TRUE)
     * @return array
     */
    public function the_author_posts(int $author_id, bool $published = true): array
    {
        $key = $this->query->helper('cache')->key(__FUNCTION__, func_get_args(), func_num_args());

        if ($this->query->helper('cache')->has($key))
        {
            return $this->query->helper('cache')->get($key);
        }

        if ($this->query->author_exists($author_id))
        {
            return $this->query->helper('cache')->set($key, $this->container->PostManager->provider()->byKey('posts.author_id', $author_id, false, $published));
        }

        return $this->query->helper('cache')->set($key, []);
    }
}
