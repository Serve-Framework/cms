<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\models;

use serve\utility\Str;

/**
 * Filter single posts.
 *
 * @author Joe J. Howard
 */
class Single extends FilterBase implements FilterInterface
{
    /**
     * The request type.
     *
     * @var string
     */
    protected $requestType = 'single';

    /**
     * {@inheritdoc}
     */
    public function filter(): bool
    {
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
                'match'     => 'post'
            ],
            'post_slug' =>
            [
                'condition' => '=',
                'match'     => $slug
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
