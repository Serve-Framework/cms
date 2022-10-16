<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\models;

/**
 * Filter tag request.
 *
 * @author Joe J. Howard
 */
class Tag extends FilterBase implements FilterInterface
{
    /**
     * The request type.
     *
     * @var string
     */
    protected $requestType = 'tag';

    /**
     * {@inheritdoc}
     */
    public function filter(): bool
    {
        $taxonomySlug = !empty($this->blogLocation) ? $this->urlParts[2] : $this->urlParts[1];
        $tag          = $this->TagManager->provider()->byKey('slug', $taxonomySlug, true);

        // Make sure the tag exists
        if (!$tag)
        {
            return false;
        }

        $this->Query->filter([
            'post_status' =>
            [
                'condition' => '=',
                'match'     => 'published',
            ],
            'post_type' =>
            [
                'condition' => '=',
                'match'     => 'post'
            ],
            'tag_slug' =>
            [
                'condition' => '=',
                'match'     => $tag->slug
            ],
            'sort' =>
            [
                'by'        => 'post_created',
                'direction' => 'DESC'
            ],
        ], $this->requestType());

        // If there are no posts and the page is more than 2 return false
        if ($this->Query->postCount === 0 && $this->Query->pageIndex > 1)
        {
            return false;
        }
        
        $this->Query->taxonomySlug = $tag->slug;
        
        return true;
    }
}
