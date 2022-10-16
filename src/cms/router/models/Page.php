<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\models;

use serve\utility\Str;

/**
 * Filter static page.
 *
 * @author Joe J. Howard
 */
class Page extends FilterBase implements FilterInterface
{
    /**
     * The request type.
     *
     * @var string
     */
    protected $requestType = 'page';

    /**
     * {@inheritdoc}
     */
    public function filter(): bool
    {
        $slug   = Str::getBeforeLastWord($this->Request->environment()->REQUEST_PATH, '/feed');
        $status = $this->Request->fetch('query') === 'draft' && $this->Gatekeeper->isAdmin() ? 'draft' : 'published';

        $this->Query->filter([
            'post_status' =>
            [
                'condition' => '=',
                'match'     => $status
            ],
            'post_type' =>
            [
                'condition' => '=',
                'match'     => 'page'
            ],
            'post_slug' =>
            [
                'condition' => '=',
                'match'     => $slug . '/' ,
            ],
            'per_page' => 1,
        ], $this->requestType());

        return $this->Query->postCount > 0;
    }
}
