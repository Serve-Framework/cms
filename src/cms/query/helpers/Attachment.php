<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\query\helpers;

use serve\utility\Str;

/**
 * CMS Query attachment methods.
 *
 * @author Joe J. Howard
 */
class Attachment extends Helper
{
    /**
     * If the request is for an attachment returns an array of that attachment.
     *
     * @return \cms\wrappers\Media|null
     */
    public function the_attachment()
    {
        $key = $this->query->helper('cache')->key(__FUNCTION__, func_get_args(), func_num_args());

        if ($this->query->helper('cache')->has($key))
        {
            return $this->query->helper('cache')->get($key);
        }

        if ($this->query->requestType === 'attachment')
        {
            return $this->query->helper('cache')->set($key, $this->container->MediaManager->provider()->byKey('url', $this->query->attachmentURL, true));
        }

        return null;
    }

    /**
     * If the request is for an attachment returns an array of that attachment.
     *
     * @return \cms\wrappers\Media|null
     */
    public function all_the_attachments()
    {
        $key = $this->query->helper('cache')->key(__FUNCTION__, func_get_args(), func_num_args());

        if ($this->query->helper('cache')->has($key))
        {
            return $this->query->helper('cache')->get($key);
        }

        return $this->query->helper('cache')->set($key, $this->container->MediaManager->provider()->all());
    }

    /**
     * If the request is for an attachment returns the attachment URL.
     *
     * @return string|null
     */
    public function the_attachment_url(int $id = null)
    {
        if ($id)
        {
            $attachment = $this->container->MediaManager->provider()->byId($id);

            if ($attachment)
            {
                $name   = Str::getAfterLastChar($attachment->url, '/');

                $prefix = !empty($this->query->blog_location()) ? '/' . $this->query->blog_location() . '/' : '/';

                return $this->container->Request->environment()->HTTP_HOST . $prefix . 'attachment/' . trim($name, '/') . '/';
            }
        }

        return $this->query->attachmentURL;
    }

    /**
     * If the request is for an attachment returns the attachment size suffix.
     *
     * @return string|null
     */
    public function the_attachment_size()
    {
        return $this->query->attachmentSize;
    }
}
