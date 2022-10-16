<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\models;

use serve\utility\Str;

/**
 * Filter custom post types posts.
 *
 * @author Joe J. Howard
 */
class CustomPost extends FilterBase implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function filter(): bool
    {
        $type   = Str::getAfterFirstChar($this->requestType, '-');
        $slug   = $this->postSlug();
        $status = $this->Request->fetch('query') === 'draft' && $this->Gatekeeper->isAdmin() ? 'draft' : 'published';

        $this->Query->filter([
            'post_status' =>
            [
                'condition' => '=',
                'match'     => $status,
            ],
            'post_type' =>
            [
                'condition' => '=',
                'match'     => $type,
            ],
            'post_slug' =>
            [
                'condition' => '=',
                'match'     => $slug,
            ],
            'per_page' => 1,
        ], $this->requestType());

        return $this->Query->postCount > 0;
    }

    /**
     * Returns the post slug.
     *
     * @return string
     */
    private function postSlug(): string
    {
        $slug = !empty($this->blogLocation) ? str_replace($this->blogLocation . '/', '', $this->Request->environment()->REQUEST_PATH) : $this->Request->environment()->REQUEST_PATH;

        return Str::getBeforeLastWord($slug, '/feed') . '/';
    }
}
