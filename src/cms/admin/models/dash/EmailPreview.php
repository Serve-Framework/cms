<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\models\dash;

use cms\admin\models\BaseModel;

/**
 * Admin panel sent email preview.
 *
 * @author Joe J. Howard
 */
class EmailPreview extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public function onGET(): array|false
    {
        if ($this->isLoggedIn())
        {
            return $this->parseGet();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onPOST(): array|false
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function onAJAX(): array|false|string
    {
        // Process any AJAX requests here
        //
        // Returning an associative array will
        // send a JSON response to the client

        // Returning false sends a 404
        return false;
    }

    /**
     * Parse and validate the incoming get request.
     *
     * @return false|array
     */
    public function parseGet()
    {
        $id = explode('/', $this->Request->environment()->REQUEST_PATH);
        $id = array_pop($id);

        $path         = $this->Config->get('email.log_dir');
        $file         = $path . '/' . $id;
        $contentFile  = $path . '/' . $id . '_content';

        if ($this->Filesystem->exists($file) && $this->Filesystem->exists($contentFile))
        {
            $data    = unserialize($this->Filesystem->getContents($file));
            $content = $this->Filesystem->getContents($contentFile);

            if ($data['format'] !== 'html')
            {
                $this->Response->format()->set('txt');
            }

            return ['content' => $content];
        }

        return false;
    }
}
