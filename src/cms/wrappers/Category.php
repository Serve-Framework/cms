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
 * Category utility wrapper.
 *
 * @author Joe J. Howard
 */
class Category extends Wrapper
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
            $existsSlug = $this->SQL->SELECT('*')->FROM('categories')->WHERE('slug', '=', $this->data['slug'])->ROW();

            if ($existsSlug && $existsSlug['id'] !== $this->id)
            {
                $saved = $this->SQL->INSERT_INTO('categories')->VALUES($this->data)->EXEC();

                if ($saved)
                {
                    $this->data['id'] = intval($this->SQL->connectionHandler()->lastInsertId());
                }
            }
            else
            {
                $saved = $this->SQL->UPDATE('categories')->SET($this->data)->WHERE('id', '=', $this->id)->EXEC();
            }
        }
        else
        {
            $saved = $this->SQL->INSERT_INTO('categories')->VALUES($this->data)->EXEC();

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
                throw new InvalidArgumentException(vsprintf("%s(): The 'uncategorized' taxonomy is not deletable.", [__METHOD__]));
            }

            if ($this->removeAllJoins())
            {
                return $this->SQL->DELETE_FROM('categories')->WHERE('id', '=', $this->data['id'])->EXEC() ? true : false;
            }
        }

        return false;
    }

    /**
     * Clears all posts from the category.
     *
     * @throws \InvalidArgumentException If this is category id 1
     * @return bool
     */
    public function clear(): void
    {
        if (isset($this->data['id']))
        {
            if ($this->data['id'] === 1)
            {
                throw new InvalidArgumentException(vsprintf("%s():  The 'uncategorized' taxonomy cannot be cleared.", [__METHOD__]));
            }

            $this->removeAllJoins();
        }
    }

    /**
     * Get the parent category if it is set.
     *
     * @return Category|false
     */
    public function parent()
    {
        if (isset($this->data['id']))
        {
            if ($this->data['parent_id'] > 0)
            {
                $row = $this->SQL->SELECT('*')->FROM('categories')->WHERE('id', '=', $this->data['parent_id'])->ROW();

                if ($row)
                {
                    return $this->categoryFromRow($row);
                }
            }
        }

        return false;
    }

    /**
     * Get children categories.
     *
     * @return array
     */
    public function children(): array
    {
        $children = [];

        if (isset($this->data['id']))
        {
            $cats = $this->SQL->SELECT('*')->FROM('categories')->WHERE('parent_id', '=', $this->data['id'])->FIND_ALL();

            if ($cats)
            {
                foreach ($cats as $cat)
                {
                    $children[] = $this->categoryFromRow($cat);
                }
            }
        }

        return $children;
    }

    /**
     * Create a category from a databse row.
     *
     * @param  array    $data Category database row
     * @return Category
     */
    private function categoryFromRow(array $data): Category
    {
        return new Category($this->SQL, $data);
    }

    /**
     * Unjoin all posts and reset to uncategorized if no categories left.
     *
     * @return bool
     */
    private function removeAllJoins(): bool
    {
        if (isset($this->data['id']))
        {
            // Remove post joins
            $posts = $this->SQL->SELECT('posts.*')->FROM('categories_to_posts')->LEFT_JOIN_ON('posts', 'categories_to_posts.post_id = posts.id')->WHERE('categories_to_posts.category_id', '=', $this->data['id'])->FIND_ALL();

            if ($posts)
            {
                foreach ($posts as $post)
                {
                    $postCats = $this->SQL->SELECT('*')->FROM('categories_to_posts')->WHERE('post_id', '=', $post['id'])->FIND_ALL();

                    if (count($postCats) === 1)
                    {
                        $this->SQL->INSERT_INTO('categories_to_posts')->VALUES(['post_id' => $post['id'], 'category_id' => 1])->EXEC();
                    }
                }
            }

            $this->SQL->DELETE_FROM('categories_to_posts')->WHERE('category_id', '=', $this->data['id'])->EXEC();

            // Children now have no parent
            foreach ($this->children() as $child)
            {
                $child->parent_id = 0;

                $child->save();
            }

            return true;
        }

        return false;
    }
}
