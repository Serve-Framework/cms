<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\query\helpers;

/**
 * CMS Query post iteration methods.
 *
 * @author Joe J. Howard
 */
class PostIteration extends Helper
{
    /**
     * Increment the internal pointer by 1 and return the current post
     * or just return a single post by id.
     *
     * @param  int|null                      $post_id Post id or null for next post in loop (optional) (Default NULL)
     * @return \cms\wrappers\Post|null
     */
    public function the_post(int $post_id = null)
    {
        if ($post_id)
        {
            return $this->query->helper('cache')->getPostByID($post_id);
        }

        return $this->query->_next();
    }

    /**
     * Get all the posts from the current query.
     *
     * @return array
     */
    public function the_posts(): array
    {
        return $this->query->posts;
    }

    /**
     * Returns the post count of the current page of results for the current request.
     *
     * @return int
     */
    public function the_posts_count(): int
    {
        return $this->query->postCount;
    }

    /**
     * Returns the posts per page value from the config.
     *
     * @return int
     */
    public function posts_per_page(): int
    {
        return $this->container->Config->get('cms.posts_per_page');
    }

    /**
     * Do we have posts in the loop? or does a post by id exist ?
     *
     * @param  int|null $post_id Post id or null for current loop (optional) (Default NULL)
     * @return bool
     */
    public function have_posts(int $post_id = null): bool
    {
        if ($post_id)
        {
            return !empty($this->query->helper('cache')->getPostByID($post_id));
        }

        return $this->query->postIndex < $this->query->postCount -1;
    }

    /**
     * Rewind the internal pointer to the '-1'.
     */
    public function rewind_posts(): void
    {
        $this->query->postIndex = -1;

        if ($this->query->postCount > 0)
        {
            $this->query->post = $this->query->posts[0];
        }
    }

    /**
     * Iterate to the next post and return the post object if it exists.
     *
     * @return \cms\wrappers\Post|null
     */
    public function _next()
    {
        $this->query->postIndex++;

        if (isset($this->query->posts[$this->query->postIndex]))
        {
            $this->query->post = $this->query->posts[$this->query->postIndex];
        }
        else
        {
            $this->query->post = null;
        }

        return $this->query->post;
    }

    /**
     * Iterate to the previous post.
     *
     * @return \cms\wrappers\Post|null
     */
    public function _previous()
    {
        $this->query->postIndex--;

        if (isset($this->query->posts[$this->query->postIndex]))
        {
            $this->query->post = $this->query->posts[$this->query->postIndex];
        }
        else
        {
            $this->query->post = null;
        }

        return $this->query->post;
    }
}
