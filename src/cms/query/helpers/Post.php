<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\query\helpers;

use serve\utility\Markdown;

/**
 * CMS Query post methods.
 *
 * @author Joe J. Howard
 */
class Post extends Helper
{
    /**
     * Get the current post id.
     *
     * @return int|null
     */
    public function the_post_id()
    {
        if (!empty($this->query->post))
        {
            return $this->query->post->id;
        }

        return null;
    }

    /**
     * Get the excerpt of the current post or a post by id.
     *
     * @param  int|null    $post_id Post id or null for current post (optional) (Default NULL)
     * @return string|null
     */
    public function the_excerpt(int $post_id = null)
    {
        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post)
            {
                return $post->excerpt;
            }

            return null;
        }

        if (!empty($this->query->post))
        {
            return $this->query->post->excerpt;
        }

        return null;
    }

    /**
     * Get the status of the current post or post by id.
     *
     * @param  int|null    $post_id Post id or null for current post (optional) (Default NULL)
     * @return string|null
     */
    public function the_post_status(int $post_id = null)
    {
        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post)
            {
                return $post->status;
            }

            return null;
        }

        if (!empty($this->query->post))
        {
            return $this->query->post->status;
        }

        return null;
    }

    /**
     * Get the type of the current post or post by id.
     *
     * @param  int|null    $post_id Post id or null for current post (optional) (Default NULL)
     * @return string|null
     */
    public function the_post_type(int $post_id = null)
    {
        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post)
            {
                return $post->type;
            }

            return null;
        }

        if (!empty($this->query->post))
        {
            return $this->query->post->type;
        }

        return null;
    }

    /**
     * Get the meta for the current post or post by id.
     *
     * @param  int|null $post_id Post id or null for current post (optional) (Default NULL)
     * @return array
     */
    public function the_post_meta($post_id = null): array
    {
        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post)
            {
                return $post->meta;
            }

            return [];
        }

        if (!empty($this->query->post))
        {
            return $this->query->post->meta;
        }

        return [];
    }

    /**
     * Get the created time of the current post or a post by id.
     *
     * @param  string      $format  PHP date() string format (optional) (Default 'U')
     * @param  int|null    $post_id Post id or null for current post (optional) (Default NULL)
     * @return string|null
     */
    public function the_time(string $format = 'U', int $post_id = null)
    {
        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post)
            {
                return date($format, $post->created);
            }

            return null;
        }

        if (!empty($this->query->post))
        {
            return date($format, $this->query->post->created);
        }

        return null;
    }

    /**
     * Get the last modified time of the current post or a post by id.
     *
     * @param  string      $format  PHP date() string format (optional) (Default 'U')
     * @param  int|null    $post_id Post id or null for current post (optional) (Default NULL)
     * @return string|null
     */
    public function the_modified_time(string $format = 'U', int $post_id = null)
    {
        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post)
            {
                return date($format, $post->modified);
            }

            return null;
        }

        if (!empty($this->query->post))
        {
            return date($format, $this->query->post->modified);
        }

        return null;
    }

    /**
     * Does the current post or a post by id have a thumbnail attachment.
     *
     * @param  int|null $post_id Post id or null for current post (optional) (Default NULL)
     * @return bool
     */
    public function has_post_thumbnail(int $post_id = null): bool
    {
        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post && !empty($post->thumbnail_id))
            {
                return !empty($this->query->helper('cache')->getMediaById($post->thumbnail_id));
            }

            return false;
        }

        if (!empty($this->query->post) && !empty($this->query->post->thumbnail_id))
        {
            return !empty($this->query->helper('cache')->getMediaById($this->query->post->thumbnail_id));
        }

        return false;
    }

    /**
     * Get the title of the current post or a post by id.
     *
     * @param  int|null    $post_id Post id or null for current post (optional) (Default NULL)
     * @return string|null
     */
    public function the_title(int $post_id = null)
    {
        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post)
            {
                return $post->title;
            }

            return null;
        }

        if (is_category() || is_tag() || is_author())
        {
            return the_taxonomy()->name;
        }

        if (!empty($this->query->post))
        {
            return $this->query->post->title;
        }

        return null;
    }

    /**
     * Get the full URL of the current post or a post by id.
     *
     * @param  int|null    $post_id Post id or null for current post (optional) (Default NULL)
     * @return string|null
     */
    public function the_permalink(int $post_id = null)
    {
        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post)
            {
                $prefix = !empty($this->query->blog_location()) && $post->type === 'post' ? '/' . $this->query->blog_location() . '/' : '/';

                return $this->container->Request->environment()->HTTP_HOST . $prefix . trim($post->slug, '/') . '/';
            }

            return null;
        }

        if (!empty($this->query->post))
        {
            $prefix = !empty($this->query->blog_location()) && $this->query->post->type === 'post' ? '/' . $this->query->blog_location() . '/' : '/';

            return $this->container->Request->environment()->HTTP_HOST . $prefix . trim($this->query->post->slug, '/') . '/';
        }

        return null;
    }

    /**
     * Get the slug of the current post or a post by id.
     *
     * @param  int|null    $post_id Post id or null for current post (optional) (Default NULL)
     * @return string|null
     */
    public function the_slug(int $post_id = null)
    {
        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post)
            {
                return trim($post->slug, '/') . '/';
            }

            return null;
        }

        if (!empty($this->query->post))
        {
            return trim($this->query->post->slug, '/') . '/';
        }

        return null;
    }

    /**
     * Gets the HTML content for current post or a post by id.
     *
     * @param  int|null $post_id Post id or null for current post (optional) (Default NULL)
     * @param  bool     $raw     Return raw content not HTML formatted (optional) (default false)
     * @return string
     */
    public function the_content(int $post_id = null, $raw = false): string
    {
        $content = '';

        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post)
            {
                $content = $post->content;
            }
        }
        else
        {
            if (!empty($this->query->post))
            {
                $content = $this->query->post->content;
            }
        }

        if (empty($content))
        {
            return '';
        }

        // Parse through shortcode filter
        $content = $this->container->Shortcodes->filter($content);

        if ($raw)
        {
            return Markdown::plainText(trim($content));
        }

        return Markdown::convert(trim($content));
    }

    /**
     * Gets an attachment object for the current post or a post by id.
     *
     * @param  int|null                       $post_id Post id or null for current post (optional) (Default NULL)
     * @return \cms\wrappers\Media|null
     */
    public function the_post_thumbnail(int $post_id = null)
    {
        if ($post_id)
        {
            $post = $this->query->helper('cache')->getPostByID($post_id);

            if ($post && $this->query->has_post_thumbnail($post_id))
            {
                return $this->query->helper('cache')->getMediaById($post->thumbnail_id);
            }

            return null;
        }
        elseif (!empty($this->query->post) && $this->query->has_post_thumbnail($this->query->post->id))
        {
            return $this->query->helper('cache')->getMediaById($this->query->post->thumbnail_id);
        }

        return null;
    }

    /**
     * Gets the thumbnail src for the current post or a post by id in a given size.
     *
     * @param  int|null    $post_id Post id or null for current post (optional) (Default NULL)
     * @param  string      $size    The post thumbnail size "small"|"medium"|"large"|"original" (optional) (Default 'original')
     * @return string|null
     */
    public function the_post_thumbnail_src(int $post_id = null, string $size = 'original')
    {
        if ($this->query->has_post_thumbnail($post_id))
        {
            $thumbnail = $this->query->the_post_thumbnail($post_id);

            return $thumbnail->imgSize($size);
        }

        return null;
    }

    /**
     * Prints an HTML img tag from Serve attachment object.
     *
     * @param  \cms\wrappers\Media $thumbnail The attachment to print
     * @param  string                    $size      The post thumbnail size "small"|"medium"|"large"|"original" (optional) (Default 'original')
     * @param  string|int                $width     The img tag's width attribute  (optional) (Default '')
     * @param  string|int                $height    The img tag's height attribute (optional) (Default '')
     * @param  string                    $classes   The img tag's class attribute  (optional) (Default '')
     * @param  string                    $id        The img tag's id attribute (optional) (Default '')
     * @return string
     */
    public function display_thumbnail($thumbnail, $size = 'original', $width = '', $height = '', string $classes = '', string $id = ''): string
    {
        $width    = !$width ? '' : 'width="' . intval($width) . '"';
        $height   = !$height ? '' : 'height="' . intval($height) . '"';
        $classes  = !$classes ? '' : 'class="' . $classes . '"';
        $id       = !$id ? '' : 'id="' . $id . '"';

        if (!$thumbnail)
        {
            return '<img src="_" ' . $width . ' ' . $height . ' ' . $classes . ' ' . $id . ' alt="" title="">';
        }

        $src = $thumbnail->imgSize($size);

        return '<img src="' . $src . '" ' . $width . ' ' . $height . ' ' . $classes . ' ' . $id . ' alt="' . $thumbnail->alt . '" title="' . $thumbnail->title . '" >';
    }

    /**
     * Get an array of POST objects of all static pages.
     *
     * @param  bool  $published Return only published posts (optional) (default true)
     * @return array
     */
    public function all_static_pages(bool $published = true): array
    {
        return $this->container->PostManager->provider()->byKey('posts.type', 'page', false, $published);
    }

    /**
     * Get an array of POST objects of custom post types by type.
     *
     * @param  bool  $published Return only published posts (optional) (default true)
     * @return array
     */
    public function all_custom_posts(string $type, bool $published = true): array
    {
        return $this->container->PostManager->provider()->byKey('posts.type', $type, false, $published);
    }
}
