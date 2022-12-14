<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\models\dash;

use cms\admin\models\BaseModel;
use serve\http\response\exceptions\InvalidTokenException;
use serve\http\response\exceptions\RequestException;
use serve\utility\Markdown;
use serve\utility\Str;

/**
 * Comments model.
 *
 * @author Joe J. Howard
 */
class Comments extends BaseModel
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
            'comments'      => $this->loadComments(),
            'max_page'      => 0,
            'queries'       => $this->getQueries(),
            'empty_queries' => $this->emptyQueries(),
        ];

        if (!empty($response['comments']))
        {
            $response['max_page'] = $this->loadComments(true);
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

        $commentIds = array_filter(array_map('intval', $this->post['comments']));

        if (!empty($commentIds))
        {
            if ($this->post['bulk_action'] === 'delete')
            {
                $this->delete($commentIds);

                return $this->postMessage('success', 'Your comments were successfully deleted!');
            }
            elseif ($this->post['bulk_action'] === 'update')
            {
                $this->update($commentIds[0], $this->post['content']);

                return $this->postMessage('success', 'Your comment was successfully updated!');
            }
            elseif (in_array($this->post['bulk_action'], ['spam', 'pending', 'approved']))
            {
                $this->changeStatus($commentIds, $this->post['bulk_action']);

                return $this->postMessage('success', 'Your comments were successfully updated!');
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

        if (!in_array($this->post['bulk_action'], ['spam', 'delete', 'pending', 'approved', 'update']))
        {
            throw new RequestException(500, 'Bad Admin Panel POST Request. The POST data was either not provided or was invalid.');
        }

        if (!isset($this->post['comments']) || !is_array($this->post['comments']) || empty($this->post['comments']))
        {
            throw new RequestException(500, 'Bad Admin Panel POST Request. The POST data was either not provided or was invalid.');
        }

        return true;
    }

    /**
     * Delete comments by id.
     *
     * @param array $ids List of post ids
     */
    private function delete(array $ids): void
    {
        foreach ($ids as $id)
        {
            $comment = $this->CommentManager->byId($id);

            if ($comment)
            {
                $comment->delete();
            }
        }
    }

    /**
     * Update comment content.
     *
     * @param int    $id      Comment id to update
     * @param string $content Content to set
     */
    private function update(int $id, string $content): void
    {
        $comment = $this->CommentManager->byId($id);

        if ($comment)
        {
            $comment->content = $content;

            $comment->html_content = Markdown::convert($content);

            $comment->save();
        }
    }

    /**
     * Change a list of comment statuses.
     *
     * @param array $ids List of post ids
     */
    private function changeStatus(array $ids, string $status): void
    {
        foreach ($ids as $id)
        {
            $comment = $this->CommentManager->byId($id);

            if ($comment)
            {
                $comment->status = $status;
                $comment->save();
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
            $queries['status'] === false
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

        return $queries;
    }

    /**
     * Returns the list of comments for display.
     *
     * @param  bool      $checkMaxPages Count the max pages
     * @return array|int
     */
    private function loadComments(bool $checkMaxPages = false)
    {
       // Get queries
        $queries = $this->getQueries();

        // Default operation values
        $page         = intval($queries['page']);
        $page         = $page === 1 || $page === 0 ? 0 : $page-1;
        $sort         = $queries['sort'] === 'newest' ? 'DESC' : 'ASC';
        $sortKey      = 'date';
        $perPage      = 10;
        $offset       = $page * $perPage;
        $limit        = $perPage;
        $search       = $queries['search'];
        $filter       = $queries['status'];

        // Filter and sanitize the sort order
        if ($queries['sort'] === 'name')  $sortKey   = 'name';
        if ($queries['sort'] === 'email') $sortKey   = 'email';
        if ($queries['sort'] === 'post')  $sortKey   = 'post_id';

        $this->sql()->SELECT('id')->FROM('comments');

        // Filter by status
        if ($filter === 'approved')
        {
            $this->sql()->WHERE('status', '=', 'approved');
        }
        if ($filter === 'spam')
        {
            $this->sql()->WHERE('status', '=', 'spam');
        }
        if ($filter === 'pending')
        {
            $this->sql()->WHERE('status', '=', 'pending');
        }
        if ($filter === 'deleted')
        {
            $this->sql()->WHERE('status', '=', 'pending');
        }

        // Is this a search
        if ($search)
        {
            if (Str::contains($search, ':'))
            {
                $keys = explode(':', $search);
                
                if (in_array($keys[0], ['name', 'email', 'ip_address']))
                {
                    $this->sql()->AND_WHERE($keys[0], 'LIKE', "%$keys[1]%");
                }
            }
            else
            {
                $this->sql()->AND_WHERE('content', 'LIKE', "%$search%");
            }
        }

        // Set the order
        $this->sql()->ORDER_BY($sortKey, $sort);

        // Set the limit - Only if we're returning the actual articles
        if (!$checkMaxPages)
        {
            $this->sql()->LIMIT($offset, $limit);
        }

        // Find comments
        $rows = $this->sql()->FIND_ALL();

        $rows = !$rows ? [] : $rows;

        // Are we checking the pages ?
        if ($checkMaxPages)
        {
            return ceil(count($rows) / $perPage);
        }

        // Append custom keys
        $comments = [];

        foreach ($rows as $row)
        {
            $comments[] = $this->CommentManager->byId($row['id']);
        }

        return $comments;
    }
}
