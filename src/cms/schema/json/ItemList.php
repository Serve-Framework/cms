<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\schema\json;

/**
 * ItemList generator.
 *
 * @author Joe J. Howard
 */
class ItemList extends JsonGenerator implements JsonInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function generate(): array
	{
		$response = [];

        // Single blog
        if (is_single())
        {
            return $response;
        }
        // List of posts
        elseif (is_blog_location() || is_category() || is_tag() || is_author() || is_home())
        {
            $posts = the_posts();

        	$response =
        	[
        		'@type'            => 'ItemList',
	            '@id'              => the_canonical_url() . '#list',
	            'itemListElement'  => [],
        	];

        	foreach ($posts as $i => $post)
        	{
        		$response['itemListElement'][] =
        		[
        			'@type'    => 'ListItem',
        			'@id'      => the_canonical_url() . '#listItem' . ($i + 1),
        			'position' => ($i + 1),
        			'url'      => the_permalink($post->id),
        		];
        	}

        	$response['numberOfItems'] = count($response['itemListElement']);
        }

        return $response;
	}
}
