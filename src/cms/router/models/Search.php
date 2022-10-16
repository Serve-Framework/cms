<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\models;

/**
 * Filter search request.
 *
 * @author Joe J. Howard
 */
class Search extends FilterBase implements FilterInterface
{
    /**
     * The request type.
     *
     * @var string
     */
    protected $requestType = 'search';

    /**
     * {@inheritdoc}
     */
    public function filter(): bool
    {
        $searchTerm  = $this->getSearchTerm();
    
        $this->Query->filter([
            'post_status' =>
            [
                'condition' => '=',
                'match'     => 'published'
            ],
            'post_type' =>
            [
                'condition' => '!=',
                'match'     => 'page'
            ],
            'post_title' =>
            [
                'condition' => 'LIKE',
                'match'     => $searchTerm
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

        $this->Query->searchQuery = $searchTerm;

        return true;
    }

    /**
     * Get the search term.
     *
     * @return string
     */
    private function getSearchTerm(): string
    {
        $searchTerm = $this->Request->queries('q');

        if (!$searchTerm || $searchTerm === '' || trim($searchTerm) === '')
        {
            return '';
        }

        return htmlspecialchars($searchTerm);
    }
}
