<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\models\dash;

use cms\admin\models\BaseModel;
use serve\http\response\exceptions\InvalidTokenException;
use serve\http\response\exceptions\RequestException;
use serve\utility\Arr;
use serve\utility\Str;

/**
 * Tags model.
 *
 * @author Joe J. Howard
 */
class Tags extends BaseModel
{
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
     * Parse the $_GET request variables and filter the articles for the requested page.
     *
     * @return array
     */
    private function parseGet(): array
    {
        $response = [
            'tags'         => $this->loadTags(),
            'max_page'     => 0,
            'queries'      => $this->getQueries(),
            'empty_queries' => $this->emptyQueries(),
        ];

        if (!empty($response['tags']))
        {
            $response['max_page'] = $this->loadTags(true);
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

        $tagIds = array_filter(array_map('intval', $this->post['tags']));

        if (!empty($tagIds))
        {
            if ($this->post['bulk_action'] === 'delete')
            {
                $this->delete($tagIds);

                return $this->postMessage('success', 'Your tags were successfully deleted!');
            }
            if ($this->post['bulk_action'] === 'clear')
            {
                $this->clear($tagIds);

                return $this->postMessage('success', 'Your tags were successfully cleared!');
            }
            if ($this->post['bulk_action'] === 'update')
            {
                $update = $this->update(intval($tagIds[0]));

                if ($update === 'name_exists')
                {
                    return $this->postMessage('warning', 'Could not update tag. Another tag with the same name already exists.');
                }

                if ($update === 'slug_exists')
                {
                    return $this->postMessage('warning', 'Could not update tag. Another tag with the same slug already exists.');
                }

                return $this->postMessage('success', 'Tag was successfully updated!');
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

        if (!in_array($this->post['bulk_action'], ['clear', 'delete', 'update']))
        {
            throw new RequestException(500, 'Bad Admin Panel POST Request. The POST data was either not provided or was invalid.');
        }

        if (!isset($this->post['tags']) || !is_array($this->post['tags']) || empty($this->post['tags']))
        {
            throw new RequestException(500, 'Bad Admin Panel POST Request. The POST data was either not provided or was invalid.');
        }

        return true;
    }

    /**
     * Updates a tag.
     *
     * @param  int         $id Single tag id
     * @return bool|string
     */
    private function update(int $id)
    {
        if (!isset($this->post['name']) || !isset($this->post['slug']) || !isset($this->post['description']))
        {
            return false;
        }

        $name        = trim($this->post['name']);
        $slug        = Str::slug($this->post['slug']);
        $description = trim($this->post['description']);
        $tag         = $this->TagManager->byId($id);

        if (!$tag)
        {
            return false;
        }

        // Validate tag with same name does not already exist
        $existsName = $this->TagManager->byName($name);

        if ($existsName && $existsName->id !== $id)
        {
            return 'name_exists';
        }

        // Validate tag with same slug does not already exist
        $existsSlug = $this->TagManager->bySlug($slug);

        if ($existsSlug && $existsSlug->id !== $id)
        {
            return 'slug_exists';
        }

        $tag->name = $name;
        $tag->slug = $slug;
        $tag->description = $description;
        $tag->save();

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
            $tag = $this->TagManager->byId($id);

            if ($tag)
            {
                $tag->delete();
            }
        }
    }

    /**
     * Clear tags of articles.
     *
     * @param array $ids List of post ids
     */
    private function clear(array $ids): void
    {
        foreach ($ids as $id)
        {
            $tag = $this->TagManager->byId($id);

            if ($tag)
            {
                $tag->clear();
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
            $queries['sort']   === 'name'
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
        if (!isset($queries['search'])) $queries['search'] = false;
        if (!isset($queries['page']))   $queries['page']   = 0;
        if (!isset($queries['sort']))   $queries['sort']   = 'name';

        return $queries;
    }

    /**
     * Returns the list of tags for display.
     *
     * @param  bool      $checkMaxPages Count the max pages
     * @return array|int
     */
    private function loadTags(bool $checkMaxPages = false)
    {
       // Get queries
        $queries = $this->getQueries();

        // Default operation values
        $page         = ((int) $queries['page']);
        $page         = $page === 1 || $page === 0 ? 0 : $page-1;
        $sort         = 'ASC';
        $sortKey      = 'name';
        $perPage      = 10;
        $offset       = $page * $perPage;
        $limit        = $perPage;
        $search       = $queries['search'];
        $madeWhere    = false;

        // Select the posts
        $this->sql()->SELECT('tags.id')->FROM('tags');

        // Set the limit - Only if we're returning the actual tag list
        // and not sorting by article count
        if (!$checkMaxPages && $queries['sort'] === 'name')
        {
            $this->sql()->LIMIT($offset, $limit);

            $this->sql()->ORDER_BY($sortKey, $sort);
        }

        // Search the name
        if ($search)
        {
            $this->sql()->WHERE('name', 'LIKE', '%' . $queries['search'] . '%');
        }

        // Find the articles
        $rows = $this->sql()->FIND_ALL();

        $rows = !$rows ? [] : $rows;

        // Are we checking the pages ?
        if ($checkMaxPages)
        {
            return ceil(count($rows) / $perPage);
        }

        // Add all the article count
        $result = [];
        foreach ($rows as $row)
        {
            $this->sql()->SELECT('posts.id')->FROM('posts')
            ->LEFT_JOIN_ON('tags_to_posts', 'posts.id = tags_to_posts.post_id')
            ->LEFT_JOIN_ON('tags', 'tags.id = tags_to_posts.tag_id');

            if (!$madeWhere)
            {
                $this->sql()->WHERE('tags.id', '=', $row['id']);

                $madeWhere = true;
            }
            else
            {
                $this->sql()->AND_WHERE('tags.id', '=', $row['id']);
            }

            $tag = $this->TagManager->byId($row['id']);

            $count = $this->sql()->FIND_ALL();

            $tag->article_count = !$count ? 0 : count($count);

            $result[] = $tag;
        }

        // If we're sorting by article count, we need to paginate
        // all the results and return the requested page of tags
        if ($queries['sort'] !== 'name' && !$checkMaxPages)
        {
            $result = Arr::sortMulti($result, 'article_count');

            $result = Arr::paginate($result, $page, $perPage);

            if (isset($result[0]))
            {
                return $result[0];
            }

            return [];
        }

        return $result;
    }
}
