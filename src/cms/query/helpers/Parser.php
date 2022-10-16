<?php

namespace cms\query\helpers;

use InvalidArgumentException;
use serve\utility\Str;

/**
 * Query Parser.
 *
 * This class is used by \kanso\query\Query to parse a string
 * query on the the database and return the results
 */
class Parser extends Helper
{
    /**
     * The input query to parse.
     *
     * @var array
     */
    protected $queryArr;

    /**
     * Accepted logical operators.
     *
     * @var array
     */
    protected const ACCEPTED_OPERATORS  =
    [
        '=', '!=', '>', '<', '>=', '<=', 'LIKE', 'IN', 'NOT IN',
    ];

    /**
     * Accepted query keys > table key.
     *
     * @var array
     */
    protected const ACCEPTED_KEYS  =
    [
        'post_id'        => 'id',
        'post_created'   => 'created',
        'post_modified'  => 'modified',
        'post_status'    => 'status',
        'post_type'      => 'type',
        'post_slug'      => 'slug',
        'post_title'     => 'title',
        'post_excerpt'   => 'excerpt',
        'post_thumbnail' => 'thumbnail',

        'tag_id'         => 'tags.id',
        'tag_name'       => 'tags.name',
        'tag_slug'       => 'tags.slug',

        'category_id'    => 'categories.id',
        'category_name'  => 'categories.name',
        'category_slug'  => 'categories.slug',

        'author_id'       => 'users.id',
        'author_username' => 'users.username',
        'author_email'    => 'users.email',
        'author_name'     => 'users.name',
        'author_slug'     => 'users.slug',
        'author_facebook' => 'users.facebook',
        'author_twitter'  => 'users.twitter',
        'author_gplus'    => 'users.gplus',
        'author_thumbnail'=> 'users.thumbnail',

        'sort'            => 'orderBy',
        'per_page'        => 20,
        'page'            => 1,
    ];

    /**
     * Keys used for sorting, pagination.
     *
     * @var array
     */
    protected const NON_TABLE_KEYS = ['sort', 'per_page', 'page'];

    /**
     * Parse a query and return the posts.
     *
     * @param  array $queryArr Query filter array
     * @return array
     */
    public function parseQuery(array $queryArr): array
    {
        $this->queryArr = $queryArr;

        if (empty($this->queryArr))
        {
            return [];
        }

        $this->validateQuery();

        return $this->parse();
    }

    /**
     * Returns count of max posts
     *
     * @return int
     */
    public function maxPosts(): int
    {
        return $this->parse(true);
    }

    /**
     * Validate the incoming query array filter.
     *
     * @throws \InvalidArgumentException
     */
    protected function validateQuery(): void
    {
        foreach($this->queryArr as $key => $filter)
        {
            if (!isset(self::ACCEPTED_KEYS[$key]))
            {
                throw new InvalidArgumentException('Invalid query filter [' . $key . ']. Filters must be one of [' . implode(', ', self::ACCEPTED_KEYS) . ']');
            }

            if ($key === 'per_page' || $key === 'page')
            {
                if (!is_int($filter))
                {
                    throw new InvalidArgumentException('Invalid [' . $key . '] value [' . var_export($filter, true) . ']. [' . $key . '] should be a integer.');
                }
            }
            else if ($key === 'sort')
            {
                $this->validateSortFilter($filter);
            }
            else
            {
                $this->validateTableFilter($filter);
            }
        }
    }

    /**
     * Validates a table filter.
     *
     * @throws \InvalidArgumentException
     */
    protected function validateTableFilter(array $filter, ?bool $nested = false): void
    {
        if (!$nested && !is_array($filter))
        {
            throw new InvalidArgumentException('Invalid query filter [' . var_export($filter, true) . ']. Filters must be one of [' . implode(', ', self::ACCEPTED_KEYS) . ']');
        }

        if (isset($filter[0]))
        {
            foreach($filter as $orFilter)
            {
                $this->validateTableFilter($orFilter, true);
            }
        }
        else
        {
            if (!isset($filter['condition']) || !is_string($filter['condition']))
            {
                throw new InvalidArgumentException('Invalid query filter [' . var_export($filter, true) . ']. Filters must have a [condition] value.');
            }

            if (!in_array($filter['condition'], self::ACCEPTED_OPERATORS))
            {
                throw new InvalidArgumentException('Invalid query condition [' . $filter['condition'] . ']. Filters conditions should be one of [' .implode(', ', self::ACCEPTED_OPERATORS) . ']');
            }

            if (!isset($filter['match']))
            {
                throw new InvalidArgumentException('Invalid query filter [' . var_export($filter, true) . ']. Filters must have a [match] value.');
            }

            if (($filter['condition'] === 'IN' || $filter['condition'] === 'NOT IN') && !is_array($filter['match']))
            {
                throw new InvalidArgumentException('Invalid query match [' . $filter['match'] . ']. [match] must be an array when using the [IN or NOT IN] conditions.');
            }
        }
    }

    /**
     * Validate a sort filter
     *
     * @throws \InvalidArgumentException
     */
    protected function validateSortFilter($filter): void
    {
        $byKeys = array_diff(array_values(array_flip(self::ACCEPTED_KEYS)), self::NON_TABLE_KEYS);

        if (!is_array($filter) || !isset($filter['by']))
        {
            throw new InvalidArgumentException('Invalid [sort] value [' . var_export($filter, true) . '].' . " [sort] should be provided as an array e.g ['by' => 'posts', 'direction' => 'DESC']'");
        }
        if (is_array($filter['by']) || str_contains($filter['by'], ','))
        {
            $by = !is_array($filter['by']) ? array_map('trim', explode(',', $filter['by'])) : $filter['by'];

            foreach($by as $subBy)
            {
                if (!in_array($subBy, $byKeys))
                {
                    throw new InvalidArgumentException('Invalid [sort][by] value [' . var_export($subBy, true) . ']. [sort][by] must be one of [' . implode(', ', $byKeys) . ']');
                }
            }
        }
        else
        {
            if (!in_array($filter['by'], $byKeys))
            {
                throw new InvalidArgumentException('Invalid [sort][by] value [' . var_export($filter['by'], true) . ']. [sort][by] must be one of [' . implode(', ', $byKeys) . ']');
            }
        }
        if (isset($filter['direction']) && !in_array($filter['direction'], ['ASC', 'DESC']))
        {
            throw new InvalidArgumentException('Invalid [sort][direction] value [' . var_export($filter['direction'], true) . ']. [sort][direction] must be one of [ASC, DESC]');
        }
    }

    /**
     * Parse query and return DB results.
     *
     * @param  bool $count Return the maximum number of posts with the filters applied (optional) (default false)
     * @return array|int
     */
    protected function parse(bool $count = false): array|int
    {
        $this->sql()->SELECT('posts.id')->FROM('posts');

        $standardKeys = array_diff(array_values(array_flip(self::ACCEPTED_KEYS)), self::NON_TABLE_KEYS);

        $madeWhere = false;

        foreach($this->queryArr as $key => $filter)
        {
            if (in_array($key, $standardKeys))
            {
                $bdkey = self::ACCEPTED_KEYS[$key];

                // nested
                if (isset($filter[0]))
                {
                    foreach($filter as $i => $_filter)
                    {
                        $operator = $filter['condition'];
                        $value    = $filter['match'];

                        if ($operator === 'LIKE')
                        {
                            $value = '%' . str_replace('%', '', $value) . '%';
                        }

                        if ($i > 0)
                        {
                            $this->sql()->OR_WHERE($bdkey, $operator, $value);
                        }
                        else if ($madeWhere)
                        {
                            $this->sql()->AND_WHERE($bdkey, $operator, $value);
                        }
                        else
                        {
                            $this->sql()->WHERE($bdkey, $operator, $value);

                            $madeWhere = true;
                        }
                    }
                }
                else
                {
                    $operator = $filter['condition'];
                    $value    = $filter['match'];

                    if ($operator === 'LIKE')
                    {
                        $value = '%' . str_replace('%', '', $value) . '%';
                    }

                    if ($madeWhere)
                    {
                        $this->sql()->AND_WHERE($bdkey, $operator, $value);
                    }
                    else
                    {
                        $this->sql()->WHERE($bdkey, $operator, $value);

                        $madeWhere = true;
                    }
                }
            }
            else if ($key === 'sort')
            {
                $cols    = [];
                $columns = !is_array($filter['by']) ? array_map('trim', explode(',', $filter['by'])) : $filter['by'];

                foreach($columns as $column)
                {
                    $cols[] = self::ACCEPTED_KEYS[$column];
                }

                $this->sql()->ORDER_BY($cols, isset($filter['direction']) ? $filter['direction'] : null);
            }
        }

        $this->sql()->LEFT_JOIN_ON('users', 'users.id = posts.author_id');

        $this->sql()->LEFT_JOIN_ON('comments', 'comments.post_id = posts.id');

        $this->sql()->LEFT_JOIN_ON('categories_to_posts', 'posts.id = categories_to_posts.post_id');

        $this->sql()->LEFT_JOIN_ON('categories', 'categories.id = categories_to_posts.category_id');

        $this->sql()->LEFT_JOIN_ON('tags_to_posts', 'posts.id = tags_to_posts.post_id');

        $this->sql()->LEFT_JOIN_ON('tags', 'tags.id = tags_to_posts.tag_id');

        $this->sql()->GROUP_BY('posts.id');

        if ($count)
        {
            $results = $this->sql()->FIND_ALL();

            return !$results ? 0 : count($results);
        }

        $offset = ($this->queryArr['per_page'] * $this->queryArr['page']) - $this->queryArr['per_page'];

        $this->sql()->LIMIT($offset, $this->queryArr['per_page']);

        $posts = $this->sql()->FIND_ALL();

        $postObjects = [];

        if ($posts)
        {
            // ROW returned
            if ($this->queryArr['per_page'] === 1)
            {
                $posts = [$posts];
            }

            foreach ($posts as $post)
            {
                $postObjects[] = $this->container->get('PostManager')->provider()->byId($post['id']);
            }
        }

        return $postObjects;
    }
}
