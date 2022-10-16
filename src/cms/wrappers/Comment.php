<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\wrappers;

use serve\database\wrappers\Wrapper;

/**
 * Comment utility wrapper.
 *
 * @author Joe J. Howard
 */
class Comment extends Wrapper
{
    /**
     * Array of comment children.
     *
     * @var array
     */
    private $children;

    /**
     * Parent comment.
     *
     * @var \cms\wrappers\Comment
     */
    private $parent;

    /**
     * {@inheritdoc}
     */
    public function save(): bool
	{
        $saved = false;

        if (isset($this->data['id']) && isset($this->data['post_id']))
        {
            $saved = $this->SQL->UPDATE('comments')->SET($this->data)->WHERE('id', '=', $this->data['id'])->EXEC();
        }
        else
        {
            $saved = $this->SQL->INSERT_INTO('comments')->VALUES($this->data)->EXEC();

            if ($saved)
            {
                $this->data['id'] = intval($this->SQL->connectionHandler()->lastInsertId());
            }
        }

        return !$saved ? false : true;
	}

    /**
     * {@inheritdoc}
     */
    public function delete(): bool
	{
        if (isset($this->data['id']) && isset($this->data['post_id']))
        {
            if ($this->deleteCommentChildren($this))
            {
                return $this->SQL->DELETE_FROM('comments')->WHERE('id', '=', $this->data['id'])->EXEC() ? true : false;
            }
        }

        return false;
	}

    /**
     * Creates and returns a nested array of comment children.
     *
     * @return array
     */
    public function children(): array
    {
        if (is_null($this->children))
        {
           $this->children = $this->commentChildren($this);
        }

        return $this->children;
    }

    /**
     * Returns the parent Comment if it exists.
     *
     * @return \cms\wrappers\Comment|false
     */
    public function parent()
    {
        if (is_null($this->parent))
        {
           $this->parent = $this->commentParent();
        }

        return $this->parent;
    }

    /**
     * Recursively delete comment tree.
     *
     * @param  \cms\wrappers\Comment $comment Comment
     * @return bool
     */
    private function deleteCommentChildren(Comment $comment): bool
    {
        foreach ($comment->children() as $child)
        {
            $this->deleteCommentChildren($child);

            $this->SQL->DELETE_FROM('comments')->WHERE('id', '=', $child->id)->EXEC();
        }

        return true;
    }

    /**
     * Recursively build comment tree.
     *
     * @param  \cms\wrappers\Comment $comment Comment
     * @return array
     */
    private function commentChildren(Comment $comment): array
    {
        $children = [];

        $rows = $this->SQL->SELECT('*')->FROM('comments')->WHERE('parent', '=', $comment->id)->FIND_ALL();

        if ($rows)
        {
            foreach ($rows as $row)
            {
                $child = new Comment($this->SQL, $row);

                $child->children = $child->children();

                array_push($children, $child);
            }
        }

        return $children;
    }

    /**
     * Returns the parent comment.
     *
     * @return \cms\wrappers\Comment|false
     */
    private function commentParent()
    {
        if (!$this->parent_id)
        {
            return false;
        }

        $parent = $this->SQL->SELECT('*')->FROM('comments')->WHERE('id', '=', $this->parent_id)->ROW();

        if ($parent)
        {
            return new Comment($this->SQL, $parent);
        }

        return false;
    }
}
