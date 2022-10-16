<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\query;

use InvalidArgumentException;
use cms\query\helpers\Attachment;
use cms\query\helpers\Author;
use cms\query\helpers\Cache;
use cms\query\helpers\Category;
use cms\query\helpers\Comment;
use cms\query\helpers\Helper;
use cms\query\helpers\Meta;
use cms\query\helpers\Pagination;
use cms\query\helpers\Parser;
use cms\query\helpers\Post;
use cms\query\helpers\PostIteration;
use cms\query\helpers\Scripts;
use cms\query\helpers\Search;
use cms\query\helpers\Tag;
use cms\query\helpers\Templates;
use cms\query\helpers\Urls;
use cms\query\helpers\Validation;
use serve\ioc\Container;
use RuntimeException;

/**
 * CMS Query object.
 *
 * @author Joe J. Howard
 */
class Query
{
    /**
     * The page request type.
     *
     * @var string|null
     */
    public $requestType = 'custom';

    /**
     * The string-query to use on the database.
     *
     * @var array
     */
    public $queryFilter;

    /**
     * Current page request if it exists.
     *
     * @var int
     */
    public $pageIndex = 1;

    /**
     * Current post index of paginated array of posts.
     *
     * @var int
     */
    public $postIndex = -1;

    /**
     * Current post count.
     *
     * @var int
     */
    public $postCount = 0;

    /**
     * Posts per page
     *
     * @var int
     */
    public $per_page = 10;

    /**
     * Array of posts from query result.
     *
     * @var array
     */
    public $posts = [];

    /**
     * The current post.
     *
     * @var \cms\wrappers\Post|null
     */
    public $post = null;

    /**
     * Current taxonomy slug if applicable (e.g tag, category, author).
     *
     * @var string|null
     */
    public $taxonomySlug;

    /**
     * Current attachment URL: if applicable (e.g foo.com/app/public/uploads/my-image_large.png).
     *
     * @var string|null
     */
    public $attachmentURL;

    /**
     * Current attachment size: if applicable (image_large).
     *
     * @var string|null
     */
    public $attachmentSize;

    /**
     * Search term if applicable.
     *
     * @var string|null
     */
    public $searchQuery;

    /**
     * Header scripts.
     *
     * @var array
     */
    public $headerScripts = [];

    /**
     * Header scripts.
     *
     * @var array
     */
    public $headerStyles = [];

    /**
     * Footer scripts.
     *
     * @var array
     */
    public $footerScripts = [];

    /**
     * IoC container instance.
     *
     * @var \serve\ioc\Container
     */
    protected $container;

    /**
     * Helper classes.
     *
     * @var array
     */
    protected $helperClasses =
    [
        'attachment'    => Attachment::class,
        'author'        => Author::class,
        'cache'         => Cache::class,
        'category'      => Category::class,
        'comment'       => Comment::class,
        'meta'          => Meta::class,
        'pagination'    => Pagination::class,
        'post'          => Post::class,
        'postIteration' => PostIteration::class,
        'search'        => Search::class,
        'tag'           => Tag::class,
        'templates'     => Templates::class,
        'scripts'       => Scripts::class,
        'urls'          => Urls::class,
        'validation'    => Validation::class,
        'parser'        => Parser::class,
    ];

    /**
     * Helper classes.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Constructor.
     *
     * @param \serve\ioc\Container $container IoC container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Create and return a new Query object.
     *
     * @param  array  $queryFilter Query to filter posts
     * @return $this
     */
    public function create(array $queryFilter = [], string $requestType = 'custom'): Query
    {
        $instance = new Query($this->container);

        $instance->filter($queryFilter, $requestType);

        return $instance;
    }

    /**
     * Apply a query for a custom string.
     *
     * @param array $queryFilter    Query string to parse
     * @param string $requestType Request type (optional) (default 'custom')
     */
    public function filter(array $queryFilter, $requestType = 'custom'): void
    {
        $this->reset();

        if (!isset($queryFilter['page']) || $queryFilter['page'] === 0)
        {
            $queryFilter['page'] = $this->fetchPageIndex();
        }

        if (!isset($queryFilter['per_page']) || $queryFilter['per_page'] === 0)
        {
            $queryFilter['per_page'] = $this->container->Config->get('cms.posts_per_page');
        }

        $this->queryFilter = $queryFilter;

        $this->requestType = $requestType;

        $this->posts = $this->helper('parser')->parseQuery($this->queryFilter);

        $this->postCount = count($this->posts);

        $this->per_page = $queryFilter['per_page'];

        $this->pageIndex = $queryFilter['page'];

        if (isset($this->posts[0]))
        {
            $this->post = $this->posts[0];
        }
    }

    /**
     * Override the __call method to helper classes.
     *
     * @param string $method Method name
     * @param array  $args   Method args
     */
    public function __call($method, $args)
    {
        foreach ($this->helperClasses as $key => $class)
        {
            foreach (get_class_methods($class) as $_method)
            {
                if ($_method === '__construct')
                {
                    continue;
                }

                if ($_method == $method)
                {
                    $obj = $this->helper($key);

                    return call_user_func_array([$obj, $method], $args);
                }
            }
        }

        throw new InvalidArgumentException('Function [' . $method . '] not found.');
    }

    /**
     * Retrieves and returns a helper class by name.
     *
     * @param  string                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          $name Name of helper class
     * @throws \InvalidArgumentException                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       If class does not exist
     * @return \cms\query\helpers\Attachment|\cms\query\helpers\Author|\cms\query\helpers\Cache|\cms\query\helpers\Category|\cms\query\helpers\Comment|\cms\query\helpers\Helper|\cms\query\helpers\Meta|\cms\query\helpers\Pagination|\cms\query\helpers\Parser|\cms\query\helpers\Post|\cms\query\helpers\PostIteration|\cms\query\helpers\Search|\cms\query\helpers\Tag|\cms\query\helpers\Templates|\cms\query\helpers\Urls|\cms\query\helpers\Validation|\cms\query\helpers\Scripts
     */
    public function helper(string $name): Helper
    {
        if (isset($this->helpers[$name]))
        {
            return $this->helpers[$name];
        }

        foreach ($this->helperClasses as $key => $class)
        {
            if ($key === $name)
            {
                $class = new $class($this->container, $this);

                $this->helpers[$key] = $class;

                return $class;
            }
        }

        throw new InvalidArgumentException('Invalid helper class. Class "' . $name . '" does not exist.');
    }

    /**
     * Adds a new helper class.
     *
     * @param string $key   The class key
     * @param mixed  $class The helper class instance
     */
    public function addHelper(string $key, $class): void
    {
        if (is_string($class) && class_exists($class))
        {
            $this->helperClasses[$key] = $class;

            return;
        }
        elseif (!is_subclass_of($class, Helper::class))
        {
            throw new RuntimeException('Error adding Helper to Query. The class [' . $key . '] must extend [' . Helper::class . ']');
        }

        $this->helpers[$key] = $class;

        $this->helperClasses[$key] = get_class($class);
    }

    /**
     * Reset the internal properties to default.
     */
    public function reset(): void
    {
        $this->pageIndex    = 1;
        $this->per_page     = 10;
        $this->postIndex    = -1;
        $this->postCount    = 0;
        $this->posts        = [];
        $this->requestType  = null;
        $this->queryFilter  = [];
        $this->post         = null;
        $this->taxonomySlug = null;
        $this->searchQuery  = null;
    }

    /**
     * Fetch and set the currently requested page.
     *
     * @return int
     */
    protected function fetchPageIndex(): int
    {
        $pageIndex = $this->container->Request->fetch('page');

        return $pageIndex < 1 ? 1 : $pageIndex;
    }
}
