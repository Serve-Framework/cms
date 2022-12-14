<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\models;

/**
 * Filter category request.
 *
 * @author Joe J. Howard
 */
class Category extends FilterBase implements FilterInterface
{
    /**
     * The request type.
     *
     * @var string
     */
    protected $requestType = 'category';

    /**
     * {@inheritdoc}
     */
    public function filter(): bool
    {
        // Get url parts
        $urlParts  = $this->filterUrlParts();
        $category  = $this->CategoryManager->provider()->byKey('slug', array_slice($urlParts, -1)[0], true);

        // Make sure the category exists
        if (!$category)
        {
            return false;
        }

        // Make sure the path to a nested category is correct
        if (!$this->Query->the_category_slug($category->id) === implode('/', $urlParts))
        {
            return false;
        }

        // Filter the posts
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
            'category_id' =>
            [
                'condition' => '=',
                'match'     => $category->id,
            ],
            'sort' =>
            [
                'by'         => 'post_created',
                'direction'  => 'DESC',
            ],
           
        ], $this->requestType());

        // If there are no posts and the page is more than 2 return false
        if ($this->Query->postCount === 0 && $this->Query->pageIndex > 1)
        {
            return false;
        }

        $this->Query->taxonomySlug = $category->slug;

        return true;
    }

    /**
     * Filters and sanitizes URL into pieces.
     *
     * @return array
     */
    private function filterUrlParts(): array
    {
        $urlParts     = $this->urlParts;
        $isPage       = in_array('page', $urlParts);
        $isFeed       = in_array('feed', $urlParts);

        // Remove the blog prefix
        if (!empty($this->blogLocation))
        {
            array_shift($urlParts);
        }

        // remove category
        array_shift($urlParts);

        // Remove /page/number/
        if ($isPage)
        {
            array_pop($urlParts);
            array_pop($urlParts);
        }

        // Remove /feed/rss
        if ($isFeed)
        {
            $last = array_values(array_slice($urlParts, -1))[0];

            if ($last === 'rss' || $last === 'rdf' || $last == 'atom')
            {
                array_pop($urlParts);
                array_pop($urlParts);
            }
            else
            {
                array_pop($urlParts);
            }
        }

        return array_values($urlParts);
    }
}
