<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\query\helpers;

/**
 * CMS Query search methods.
 *
 * @author Joe J. Howard
 */
class Search extends Helper
{
    /**
     * Returns the searched query for search result requests.
     *
     * @return string|null
     */
    public function search_query()
    {
        if ($this->query->is_search())
        {
            return urldecode($this->query->searchQuery);
        }

        return null;
    }

    /**
     * Return the HTML for the search form.
     *
     * @return string
     */
    public function get_search_form(): string
    {
        // Load from template if it exists
        $formTemplate = $this->query->theme_directory() . DIRECTORY_SEPARATOR . 'searchform.php';

        if (file_exists($formTemplate))
        {
            return $this->query->include_template('searchform');
        }

        return '

            <form role="search" method="get" action="' . $this->query->home_url() . '/search-results/">

                <fieldset>
                        
                        <label for="search_input">Search: </label>
                        
                        <input type="search" name="q" id="search_input" placeholder="Search...">

                        <button type"submit">Search</button>

                </fieldset>
                
            </form>

        ';
    }
}
