<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\models;

/**
 * Filter author request.
 *
 * @author Joe J. Howard
 */
class Author extends FilterBase implements FilterInterface
{
    /**
     * The request type.
     *
     * @var string
     */
    protected $requestType = 'author';

    /**
     * {@inheritdoc}
     */
    public function filter(): bool
    {
        $authorSlug = !empty($this->blogLocation) ? $this->urlParts[2] : $this->urlParts[1];

        if ($this->authorExists($authorSlug))
        {
            return false;
        }

        $this->Query->filter([
            'post_status' =>
            [
                'condition' => '=',
                'match'     => 'published'
            ],
            'post_type' =>
            [
                'condition' => '=',
                'match'     => 'post'
            ],
            'sort' =>
            [
                'by'         => 'post_created',
                'direction'  => 'DESC',
            ],
            'author_slug' =>
            [
                'condition' => '=',
                'match'     => $authorSlug,
            ],
        ], $this->requestType());

        $this->Query->taxonomySlug = $authorSlug;

        return true;
    }

    /**
     * Checks if the give author is exists.
     */
    private function authorExists(string $slug): bool
    {
        $role = $this->sql()->SELECT('role')->FROM('users')->WHERE('slug', '=', $slug)->ROW();

        return $role || ($role && isset($role['role']) && $role['role'] !== 'administrator' && $role['role'] !== 'writer') ? false : true;
    }
}
