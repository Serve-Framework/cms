<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\schema\json;

/**
 * Image generator.
 *
 * @author Joe J. Howard
 */
class Image extends JsonGenerator implements JsonInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function generate(): array
	{
        $images = $this->Config->get('cms.schema.images');

        if (is_single() || is_page())
        {
            $image = the_post_thumbnail();

            if ($image)
            {
                $images =
                [
                    $image->imgSize(),
                    $image->imgSize('1_1'),
                    $image->imgSize('4_3'),
                    $image->imgSize('16_9'),
                ];
            }
        }

        elseif (is_author())
        {
            $image = the_author_thumbnail(the_taxonomy()->id);

            if ($image)
            {
                $images =
                [
                    $image->imgSize(),
                    $image->imgSize('1_1'),
                    $image->imgSize('4_3'),
                    $image->imgSize('16_9'),
                ];
            }
        }

        return
        [
            '@type' => 'ImageObject',
            '@id'   => the_canonical_url() . '#primaryimage',
            'url'   => $images,
        ];
	}
}
