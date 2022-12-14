<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\models;

use serve\utility\Str;

/**
 * Filter attachment request.
 *
 * @author Joe J. Howard
 */
class Attachment extends FilterBase implements FilterInterface
{
    /**
     * The request type.
     *
     * @var string
     */
    protected $requestType = 'attachment';

    /**
     * {@inheritdoc}
     */
    public function filter(): bool
    {
        $attachmentName  = $this->urlParts[1];
        $attachmentSlug  = Str::getBeforeLastChar($attachmentName, '.');
        $attachemmentExt = Str::getAfterLastChar($attachmentName, '.');
        $uploadsUrl      = str_replace($this->Request->environment()->DOCUMENT_ROOT, $this->Request->environment()->HTTP_HOST, $this->Config->get('cms.uploads.path'));
        $attachmentURL   = "{$uploadsUrl}/{$attachmentSlug}.{$attachemmentExt}";
        $attachment      = $this->MediaManager->provider()->byKey('url', $attachmentURL, true);

        // 404 If the attachment does not exist
        if (!$attachment)
        {
            return false;
        }

        $postRow =
        [
            'created'          => $attachment->date,
            'modified'         => $attachment->date,
            'status'           => 'published',
            'type'             => 'attachment',
            'author_id'        => $attachment->uploader_id,
            'title'            => $attachment->title,
            'excerpt'          => $attachment->alt,
            'thumbnail_id'     => $attachment->id,
            'comments_enabled' => -1,
        ];

        $this->Query->attachmentSize = 'original';
        $this->Query->attachmentURL  = $attachmentURL;
        $this->Query->queryFilter    = [];
        $this->Query->posts          = [$this->PostManager->provider()->newPost($postRow)];
        $this->Query->postCount      = 1;
        $this->Query->requestType    = $this->requestType();

        return true;
    }
}
