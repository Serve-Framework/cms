<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\wrappers;

use InvalidArgumentException;
use serve\utility\Str;
use serve\database\wrappers\Wrapper;

/**
 * Tag utility wrapper.
 *
 * @author Joe J. Howard
 */
class Tag extends Wrapper
{
    /**
     * {@inheritdoc}
     */
    public function __set(string $key, $value): void
    {
        $this->data[$key] = $key === 'slug' ? Str::slug($value) : $value;
    }

    /**
     * {@inheritdoc}
     */
    public function save(): bool
	{
        $saved = false;

        $isExisting = isset($this->id);

        if ($isExisting)
        {
            $existsSlug = $this->SQL->SELECT('*')->FROM('tags')->WHERE('slug', '=', $this->data['slug'])->ROW();

            if ($existsSlug && $existsSlug['id'] !== $this->id)
            {
                $saved = $this->SQL->INSERT_INTO('tags')->VALUES($this->data)->EXEC();

                if ($saved)
                {
                    $this->data['id'] = intval($this->SQL->connectionHandler()->lastInsertId());
                }
            }
            else
            {
                $saved = $this->SQL->UPDATE('tags')->SET($this->data)->WHERE('id', '=', $this->id)->EXEC();
            }
        }
        else
        {
            $saved = $this->SQL->INSERT_INTO('tags')->VALUES($this->data)->EXEC();

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
        if (isset($this->data['id']))
        {
            if ($this->data['id'] === 1)
            {
                throw new InvalidArgumentException(vsprintf("%s(): The 'untagged' taxonomy is not deletable.", [__METHOD__]));
            }

            if ($this->removeAllJoins())
            {
                return $this->SQL->DELETE_FROM('tags')->WHERE('id', '=', $this->data['id'])->EXEC() ? true : false;
            }
        }

        return false;
	}

    /**
     * Clears all posts from the tag.
     *
     * @throws \InvalidArgumentException If this is tag id 1
     * @return bool
     */
    public function clear(): void
    {
        if (isset($this->data['id']))
        {
            if ($this->data['id'] === 1)
            {
                throw new InvalidArgumentException(vsprintf("%s():  The 'untagged' taxonomy cannot be cleared.", [__METHOD__]));
            }

            $this->removeAllJoins();
        }
    }

    /**
     * Unjoin all posts and reset to untagged if no tags left.
     *
     * @return bool
     */
    private function removeAllJoins(): bool
    {
        if (isset($this->data['id']))
        {
            $posts = $this->SQL->SELECT('posts.*')->FROM('tags_to_posts')->LEFT_JOIN_ON('posts', 'tags_to_posts.post_id = posts.id')->WHERE('tags_to_posts.tag_id', '=', $this->data['id'])->FIND_ALL();

            if ($posts)
            {
                foreach ($posts as $post)
                {
                    $postTags = $this->SQL->SELECT('*')->FROM('tags_to_posts')->WHERE('post_id', '=', $post['id'])->FIND_ALL();

                    if (count($postTags) === 1)
                    {
                        $this->SQL->INSERT_INTO('tags_to_posts')->VALUES(['post_id' => $post['id'], 'tag_id' => 1])->EXEC();
                    }
                }
            }

            $this->SQL->DELETE_FROM('tags_to_posts')->WHERE('tag_id', '=', $this->data['id'])->EXEC();

            return true;
        }

        return false;
    }
}
