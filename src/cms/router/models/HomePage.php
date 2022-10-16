<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\models;

/**
 * Filter posts on the blog homepage.
 *
 * @author Joe J. Howard
 */
class HomePage extends FilterBase implements FilterInterface
{
    /**
     * The request type.
     *
     * @var string
     */
    protected $requestType = 'home-page';

    /**
     * {@inheritdoc}
     */
    public function filter(): bool
    {
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
                'by'        => 'post_created',
                'direction' => 'DESC'
            ],
        ], $this->requestType());

        return $this->Query->postCount > 0;
    }
}
