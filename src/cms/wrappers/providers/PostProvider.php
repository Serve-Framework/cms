<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\wrappers\providers;

use cms\wrappers\Post;
use serve\config\Config;
use serve\database\builder\Builder;
use serve\database\wrappers\providers\UserProvider;

/**
 * Post provider.
 *
 * @author Joe J. Howard
 */
class PostProvider extends Provider
{
    /**
     * Config.
     *
     * @var \serve\config\Config
     */
    private $config;

    /**
     * Tag provider.
     *
     * @var \cms\wrappers\providers\TagProvider
     */
    private $tagProvider;

    /**
     * Category provider.
     *
     * @var \cms\wrappers\providers\CategoryProvider
     */
    private $categoryProvider;

    /**
     * Media provider.
     *
     * @var \cms\wrappers\providers\MediaProvider
     */
    private $mediaProvider;

    /**
     * User provider.
     *
     * @var \serve\database\wrappers\providers\UserProvider
     */
    private $userProvider;

    /**
     * Comment provider.
     *
     * @var \cms\wrappers\providers\CommentProvider
     */
    private $commentProvider;

    /**
     * Override inherited constructor.
     *
     * @param \serve\database\builder\Builder        $SQL              SQL query builder
     * @param \serve\config\Config                 $config           Configuration instance
     * @param \cms\wrappers\providers\TagProvider      $tagProvider      Tag provider instance
     * @param \cms\wrappers\providers\CategoryProvider $categoryProvider Category provider instance
     * @param \cms\wrappers\providers\MediaProvider    $mediaProvider    Media provider instance
     * @param \serve\database\wrappers\providers\UserProvider     $userProvider     User provider instance
     */
    public function __construct(Builder $SQL, Config $config, TagProvider $tagProvider, CategoryProvider $categoryProvider, MediaProvider $mediaProvider, CommentProvider $commentProvider, UserProvider $userProvider)
    {
        $this->SQL = $SQL;

        $this->config = $config;

        $this->tagProvider = $tagProvider;

        $this->categoryProvider = $categoryProvider;

        $this->mediaProvider = $mediaProvider;

        $this->commentProvider = $commentProvider;

        $this->userProvider = $userProvider;
    }

    /**
     * Create and return new post wrapper around a database entry.
     *
     * @param  array                    $row Row from the database
     * @return \cms\wrappers\Post
     */
    public function newPost(array $row): Post
    {
        return new Post($this->SQL, $this->config, $this->tagProvider, $this->categoryProvider, $this->mediaProvider, $this->commentProvider, $this->userProvider, $row);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $row)
    {
        $post = $this->newPost($row);

        if ($post->save())
        {
            return $post;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function byId(int $id)
    {
        return $this->byKey('id', $id, true, false);
    }

    /**
     * {@inheritdoc}
     */
    public function byKey(string $index, $value, bool $single = false, bool $published = true)
    {
        if ($index === 'id')
        {
            $index = 'posts.id';
        }

        $this->SQL->SELECT('posts.*')->FROM('posts')->WHERE($index, '=', $value)
        ->LEFT_JOIN_ON('users', 'users.id = posts.author_id')
        ->LEFT_JOIN_ON('comments', 'comments.post_id = posts.id')
        ->LEFT_JOIN_ON('categories_to_posts', 'posts.id = categories_to_posts.post_id')
        ->LEFT_JOIN_ON('categories', 'categories.id = categories_to_posts.category_id')
        ->LEFT_JOIN_ON('tags_to_posts', 'posts.id = tags_to_posts.post_id')
        ->LEFT_JOIN_ON('tags', 'tags.id = tags_to_posts.tag_id')
        ->GROUP_BY('posts.id');

        if ($published)
        {
            $this->SQL->AND_WHERE('status', '=', 'published');
        }

        if ($single)
        {
            $post = $this->SQL->ROW();

            if ($post)
            {
                return $this->newPost($post);
            }

            return null;
        }
        else
        {
            $posts = [];

            $rows = $this->SQL->FIND_ALL();

            if ($rows)
            {
                foreach ($rows as $row)
                {
                    $posts[] = $this->newPost($row);
                }
            }

            return $posts;
        }
    }
}
