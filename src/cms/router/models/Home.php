<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\models;

/**
 * Filter posts on the homepage.
 *
 * @author Joe J. Howard
 */
class Home extends FilterBase implements FilterInterface
{
    /**
     * The request type.
     *
     * @var string
     */
    protected $requestType = 'home';

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

        return true;
    }
}
