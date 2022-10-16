<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\schema\json;

/**
 * Website generator.
 *
 * @author Joe J. Howard
 */
class Profile extends JsonGenerator implements JsonInterface
{
    /**
     * {@inheritdoc}
     */
    public function generate(): array
    {
        if (is_author() && the_taxonomy()->id !== 1)
        {
            $image = the_author_thumbnail();

            return
            [
                '@type'                => 'Person',
                '@id'                  => the_canonical_url() . '#profile',
                'name'                 => the_author_name(),
                'url'                  => the_author_url(),
                'description'          => the_author_bio(),
                'mainEntityOfPage'     =>
                [
                    '@id' => the_canonical_url() . '#webpage',
                ],
                'image' =>
                [
                    '@id'   => the_canonical_url() . '#primaryimage',
                ],
            ];
        }

        return [];
    }
}
