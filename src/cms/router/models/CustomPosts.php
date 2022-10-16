<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\models;

use serve\utility\Str;

/**
 * Filter for homepage custom post types posts.
 *
 * @author Joe J. Howard
 */
class CustomPosts extends FilterBase implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function filter(): bool
    {
        $type = Str::getAfterFirstChar($this->requestType, '-');

        $this->Query->filter([
            'post_status' =>
            [
                'condition' => '=',
                'match'     => 'published'
            ],
            'post_type' =>
            [
                'condition' => '=',
                'match'     => $type,
            ],
            'sort' =>
            [
                'by'        => 'post_created',
                'direction' => 'DESC'
            ],
        ], $this->requestType());

        return $this->Query->postCount > 0;
    }
}
