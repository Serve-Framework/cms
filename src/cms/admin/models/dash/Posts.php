<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\models\dash;

use cms\admin\models\BaseModel;
use serve\http\response\exceptions\InvalidTokenException;
use serve\http\response\exceptions\RequestException;
use serve\utility\Humanizer;
use serve\utility\Str;

/**
 * Posts.
 *
 * @author Joe J. Howard
 */
class Posts extends BaseModel
{
    /**
     * the post type to filter.
     *
     * @var string
     */
    protected $postType;

    /**
     * Set the post type
     * 
     * @param string  $postType  Post type
     */
    public function setPostType(string $postType)
    {
        $this->postType = $postType;
    }

    /**
     * {@inheritdoc}
     */
    public function onGET(): array|false
    {
        if ($this->isLoggedIn())
        {
            return $this->parseGet();
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function onPOST(): array|false
    {
        if ($this->isLoggedIn())
        {
            return $this->parsePost();
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function onAJAX(): array|false|string
    {
        return false;
    }

    /**
     * Parse the $_GET request variables and filter the posts for the requested page.
     *
     * @return array
     */
    private function parseGet(): array
    {
        // Prep the response
        $response =
        [
            'posts'         => $this->loadPosts(),
            'max_page'      => 0,
            'queries'       => $this->getQueries(),
            'empty_queries' => $this->emptyQueries(),
            'postType'      => $this->postType,
            'postSlug'      => Str::getAfterLastChar($this->Request->environment()->REQUEST_PATH, '/'),
            'postName'      => Humanizer::pluralize(ucfirst(Str::camel2case($this->postType))),
        ];

        // If the posts are empty,
        // There's no need to check for max pages
        if (!empty($response['posts']))
        {
            $response['max_page'] = $this->loadPosts(true);
        }

        return $response;
    }

    /**
     * Parse and validate the POST request from any submitted forms.
     *
     * @return array|false
     */
    private function parsePost()
    {
        if (!$this->validatePost())
        {
            return false;
        }

        $postIds = array_filter(array_map('intval', $this->post['posts']));

        if (!empty($postIds))
        {
            if ($this->post['bulk_action'] === 'duplicate')
            {
                $this->duplicate($postIds[0]);

                return $this->postMessage('success', 'Your posts were successfully duplicated!');
            }
            if ($this->post['bulk_action'] === 'delete')
            {
                $this->delete($postIds);

                return $this->postMessage('success', 'Your posts were successfully deleted!');
            }
            if ($this->post['bulk_action'] === 'update')
            {
                $update = $this->update(intval($postIds[0]));

                if ($update === 'name_exists')
                {
                    return $this->postMessage('warning', 'Could not update ' . $this->postType . '. Another ' . $this->postType . ' with the same name already exists.');
                }

                if ($update === 'slug_exists')
                {
                    return $this->postMessage('warning', 'Could not update ' . $this->postType . '. Another ' . $this->postType . ' with the same slug already exists.');
                }

                return $this->postMessage('success', ucfirst($this->postType) . ' was successfully updated!');
            }
            if ($this->post['bulk_action'] === 'published' || $this->post['bulk_action'] === 'draft')
            {
                $this->changeStatus($postIds, $this->post['bulk_action']);

                return $this->postMessage('success', 'Your posts were successfully updated!');
            }
        }

        return false;
    }

    /**
     * Validates all POST variables are set.
     *
     * @return bool
     */
    private function validatePost(): bool
    {
        // Validation
        if (!isset($this->post['access_token']) || !$this->Gatekeeper->verifyToken($this->post['access_token']))
        {
            throw new InvalidTokenException('Bad Admin Panel POST Request. The CSRF token was either not provided or was invalid.');
        }

        if (!isset($this->post['bulk_action']) || empty($this->post['bulk_action']))
        {
            throw new RequestException(500, 'Bad Admin Panel POST Request. The POST data was either not provided or was invalid.');
        }

        if (!in_array($this->post['bulk_action'], ['duplicate', 'published', 'draft', 'delete', 'update']))
        {
            throw new RequestException(500, 'Bad Admin Panel POST Request. The POST data was either not provided or was invalid.');
        }

        if (!isset($this->post['posts']) || !is_array($this->post['posts']) || empty($this->post['posts']))
        {
            throw new RequestException(500, 'Bad Admin Panel POST Request. The POST data was either not provided or was invalid.');
        }

        return true;
    }

    /**
     * Updates a post.
     *
     * @param  int         $id Single post id
     * @return bool|string
     */
    private function update(int $id)
    {
        if (!isset($this->post['title']) || !isset($this->post['slug']) || !isset($this->post['excerpt']))
        {
            return false;
        }

        $title       = trim($this->post['title']);
        $slug        = Str::slug($this->post['slug']);
        $excerpt     = trim($this->post['excerpt']);
        $post        = $this->PostManager->byId($id);

        if (!$post)
        {
            return false;
        }

        // Validate post with same title does not already exist
        $existsName = $this->PostManager->provider()->byKey('title', $title, true);

        if ($existsName && $existsName->id !== $id)
        {
            return 'name_exists';
        }

        // Validate post with same slug does not already exist
        $existsSlug = $this->PostManager->provider()->byKey('slug', $slug, true);

        if ($existsSlug && $existsSlug->id !== $id)
        {
            return 'slug_exists';
        }

        $post->title   = $title;
        $post->slug    = $slug;
        $post->excerpt = $excerpt;
        $post->save();
        $this->clearPostFromCache($post->id);

        return true;
    }

    /**
     * Duplicates a post.
     *
     * @param  int  $id Single post id
     * @return bool
     */
    private function duplicate(int $id): bool
    {
        $post = $this->PostManager->byId($id);

        if (!$post)
        {
            return false;
        }

        $newPost = $this->PostProvider->newPost(
        [
            'title'            => $post->title,
            'excerpt'          => $post->excerpt,
            'thumbnail_id'     => $post->thumbnail_id,
            'status'           => $post->status,
            'type'             => $post->type,
            'author_id'        => $post->author->id,
            'content'          => $post->content,
            'comments_enabled' => $post->comments_enabled,
            'meta'             => $post->meta,
        ]);

        $newPost->categories = $post->categories;
        $newPost->tags = $post->tags;
        $newPost->save();

        return true;
    }

    /**
     * Delete articles by id.
     *
     * @param array $ids List of post ids
     */
    private function delete(array $ids): void
    {
        foreach ($ids as $id)
        {
            $post = $this->PostManager->byId($id);

            if ($post)
            {
                $this->clearPostFromCache($post->id);
                $post->delete();
            }
        }
    }

    /**
     * Change articles status.
     *
     * @param array  $ids    List of post ids
     * @param string $status Post status to change to
     */
    private function changeStatus(array $ids, string $status): void
    {
        foreach ($ids as $id)
        {
            $post = $this->PostManager->byId($id);

            if ($post)
            {
                $post->status = $status;

                $post->save();

                $this->clearPostFromCache($post->id);
            }
        }
    }

    /**
     * Check if the GET URL queries are either empty or set to defaults.
     *
     * @return bool
     */
    private function emptyQueries(): bool
    {
        $queries = $this->getQueries();

        return (
            $queries['search'] === false &&
            $queries['page']   === 0 &&
            $queries['sort']   === 'newest' &&
            $queries['status'] === false &&
            $queries['author'] === false &&
            $queries['tag'] === false &&
            $queries['category'] === false
        );
    }

    /**
     * Returns the requested GET queries with defaults.
     *
     * @return array
     */
    private function getQueries(): array
    {
        // Get queries
        $queries = $this->Request->queries();

        // Set defaults
        if (!isset($queries['search']))   $queries['search']   = false;
        if (!isset($queries['page']))     $queries['page']     = 0;
        if (!isset($queries['sort']))     $queries['sort']     = 'newest';
        if (!isset($queries['status']))   $queries['status']   = false;
        if (!isset($queries['author']))   $queries['author']   = false;
        if (!isset($queries['tag']))      $queries['tag']      = false;
        if (!isset($queries['category'])) $queries['category'] = false;

        return $queries;
    }

    /**
     * Returns the list of articles for display.
     *
     * @param  bool      $checkMaxPages Count the max pages
     * @return array|int
     */
    private function loadPosts(bool $checkMaxPages = false)
    {
        // Get queries
        $queries = $this->getQueries();

        // Default operation values
        $page         = ((int) $queries['page']);
        $page         = $page === 1 || $page === 0 ? 0 : $page-1;
        $sort         = 'ASC';
        $sortKey      = 'posts.created';
        $perPage      = 10;
        $offset       = $page * $perPage;
        $limit        = $perPage;
        $status       = $queries['status'];
        $search       = $queries['search'];
        $author       = $queries['author'];
        $tag          = $queries['tag'];
        $category     = $queries['category'];

        // Filter and sanitize the sort order
        if ($queries['sort'] === 'newest' || $queries['sort'] === 'published') $sort = 'DESC';
        if ($queries['sort'] === 'oldest' || $queries['sort'] === 'drafts') $sort = 'ASC';

        if ($queries['sort'] === 'category')  $sortKey   = 'categories.name';
        if ($queries['sort'] === 'tags')      $sortKey   = 'tags.name';
        if ($queries['sort'] === 'drafts')    $sortKey   = 'posts.status';
        if ($queries['sort'] === 'published') $sortKey   = 'posts.status';
        if ($queries['sort'] === 'type')      $sortKey   = 'posts.type';
        if ($queries['sort'] === 'title')     $sortKey   = 'posts.title';

        // Select the posts
        $this->sql()->SELECT('posts.id')->FROM('posts')->WHERE('posts.type', '=', $this->postType);

        // Set the order
        $this->sql()->ORDER_BY($sortKey, $sort);

        // Apply basic joins for queries
        $this->sql()->LEFT_JOIN_ON('users', 'users.id = posts.author_id');
        $this->sql()->LEFT_JOIN_ON('comments', 'comments.post_id = posts.id');
        $this->sql()->LEFT_JOIN_ON('categories_to_posts', 'posts.id = categories_to_posts.post_id');
        $this->sql()->LEFT_JOIN_ON('categories', 'categories.id = categories_to_posts.category_id');
        $this->sql()->LEFT_JOIN_ON('tags_to_posts', 'posts.id = tags_to_posts.post_id');
        $this->sql()->LEFT_JOIN_ON('tags', 'tags.id = tags_to_posts.tag_id');
        $this->sql()->GROUP_BY('posts.id');

        // Filter status/published
        if ($status === 'published')
        {
            $this->sql()->AND_WHERE('posts.status', '=', 'published');
        }
        elseif ($status === 'drafts')
        {
            $this->sql()->AND_WHERE('posts.status', '=', 'draft');
        }

        // Search the title
        if ($search)
        {
            $this->sql()->AND_WHERE('posts.title', 'LIKE', '%' . $queries['search'] . '%');
        }

        // Filter by author
        if ($author)
        {
            $this->sql()->AND_WHERE('posts.author_id', '=', intval($author));
        }

        // Filter by tag
        if ($tag)
        {
            $this->sql()->AND_WHERE('tags.id', '=', intval($tag));
        }

        // Filter by category
        if ($category)
        {
            $this->sql()->AND_WHERE('categories.id', '=', intval($category));
        }

        // Set the limit - Only if we're returning the actual articles
        if (!$checkMaxPages)
        {
            $this->sql()->LIMIT($offset, $limit);
        }

        // Find the articles
        $rows = $this->sql()->FIND_ALL();

        $rows = !$rows ? [] : $rows;

        // Are we checking the pages ?
        if ($checkMaxPages)
        {
            return ceil(count($rows) / $perPage);
        }

        $articles = [];

        foreach ($rows as $row)
        {
           $articles[] = $this->PostManager->byId($row['id']);
        }

        return $articles;
    }

    /**
     * Clears a post from the cache.
     *
     * @param int $postId Post id to clear
     */
    private function clearPostFromCache(int $postId): void
    {
        if ($this->Config->get('cache.http_cache_enabled') === true)
        {
            $this->Cache->delete(Str::alphaDash($this->Config->get('cms.blog_location') . '/' . $this->Query->the_slug($postId)));
        }
    }
}
