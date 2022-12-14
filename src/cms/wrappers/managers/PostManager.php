<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\wrappers\managers;

use cms\wrappers\providers\PostProvider;

/**
 * Post manager.
 *
 * @author Joe J. Howard
 */
class PostManager extends Manager
{
    /**
     * {@inheritdoc}
     */
    public function provider(): PostProvider
	{
        return $this->provider;
	}

    /**
     * Creates a new post.
     *
     * @param  array $row Entry row
     * @return mixed
     */
    public function create(array $row)
    {
        return $this->provider->create($row);
    }

	/**
	 * Gets a post by id.
	 *
	 * @param  int   $id Tag id
	 * @return mixed
	 */
	public function byId(int $id)
	{
		return $this->provider->byId($id);
	}

    /**
     * Deletes a post by id.
     *
     * @param  int  $id Post id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $post = $this->byId($id);

        if ($post)
        {
            return $post->delete() ? true : false;
        }

        return false;
    }
}
