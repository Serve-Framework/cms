<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\email\utility;

use serve\file\Filesystem;
use serve\utility\UUID;

/**
 * Email logging utility.
 *
 * @author Joe J. Howard
 */
class Log
{
    /**
     * Filesystem instance.
     *
     * @var \serve\file\Filesystem
     */
    private $filesystem;

    /**
     * Path to store logs in.
     *
     * @var string
     */
    private $path;

    /**
     * Constructor.
     *
     * @param \serve\file\Filesystem $filesystem Filesystem instance
     * @param string                           $path       Directory to log files
     */
    public function __construct(Filesystem $filesystem, string $path)
    {
        $this->path = $path;

        $this->filesystem = $filesystem;
    }

    /**
     * Write email to log.
     *
     * @param  string $toEmail     The email address to send the email to
     * @param  string $senderName  The name of the sender
     * @param  string $senderEmail The email address of the sender
     * @param  string $subject     The subject of the email
     * @param  string $content     The message to be sent
     * @param  string $format      html or plain
     * @return string
     */
    public function save(string $toEmail, string $senderName, string $senderEmail, string $subject, string $content, string $format): string
    {
        $id = UUID::v4();

        $data =
        [
            'to_email'   => $toEmail,
            'from_email' => $senderEmail,
            'from_name'  => $senderName,
            'subject'    => $subject,
            'format'     => $format,
            'date'       => time(),
        ];

        $this->filesystem->putContents($this->path . DIRECTORY_SEPARATOR . $id, serialize($data));

        $this->filesystem->putContents($this->path . DIRECTORY_SEPARATOR . $id . '_content', $content);

        return $id;
    }
}
