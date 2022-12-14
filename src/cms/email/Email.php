<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\email;

use cms\email\utility\Log;
use cms\email\utility\Queue;
use cms\email\utility\Sender;
use serve\file\Filesystem;
use serve\application\Application;

/**
 * CMS email utility.
 *
 * @author Joe J. Howard
 */
class Email
{
    /**
     * Theme for email styling.
     *
     * @var array
     */
    private $theme =
    [
        'body_bg'         => '#FFFFFF',
        'content_bg'      => '#FFFFFF',
        'content_border'  => '1px solid #DADADA',
        'font_family'     => '"Helvetica Neue", Helvetica, Arial, sans-serif',
        'font_size'       => '13.5px',
        'line_height'     => '27px',
        'body_color'      => '#4e5358',
        'link_color'      => '#62c4b6',
        'color_gray'      => '#c7c7c7',
        'color_gray_dark' => '#b1b1b1',
        'btn_bg'          => '#62c4b6',
        'btn_color'       => '#ffffff',
        'btn_hover_bg'    => '#48ad9e',
        'btn_size'        => '18px',
        'btn_font_size'   => '13px',
        'border_radius'   => '3px',
        'logo_url'        => 'https://serve-framework.github.io/logo.svg',
        'logo_link'       => 'https://serve-framework.github.io/',
        'font_size_h1'    => '30px',
        'font_size_h2'    => '28px',
        'font_size_h3'    => '24px',
        'font_size_h4'    => '20px',
        'font_size_h5'    => '18px',
        'font_size_h6'    => '15px',
    ];

    /**
     * Filesystem instance.
     *
     * @var \serve\file\Filesystem
     */
    private $filesystem;

    /**
     * Filesystem instance.
     *
     * @var \cms\email\utility\Sender
     */
    private $sender;

    /**
     * Mail logger.
     *
     * @var \cms\email\utility\Log
     */
    private $log;

    /**
     * Mail logger.
     *
     * @var \cms\email\utility\Queue
     */
    private $queue;

    /**
     * Constructor.
     *
     * @param \serve\file\Filesystem $filesystem Filesystem instance
     * @param \cms\email\utility\Log     $log        Mail logging utility
     * @param \cms\email\utility\Sender  $sender     Email sender utility
     * @param \cms\email\utility\Queue   $queue      Email queue utility
     * @param array                            $theme      Array of theme options (optional) (default [])
     */
    public function __construct(Filesystem $filesystem, Log $log, Sender $sender, Queue $queue, $theme = [])
    {
        $this->filesystem = $filesystem;

        $this->log = $log;

        $this->sender = $sender;

        $this->queue = $queue;

        $this->theme = array_merge($this->theme, $theme);
    }

    /**
     * Returns the email queue.
     *
     * @return \cms\email\utility\Queue
     */
    public function queue(): Queue
    {
        return $this->queue;
    }

    /**
     * Returns the email loger.
     *
     * @return \cms\email\utility\Log
     */
    public function log(): Log
    {
        return $this->log;
    }

    /**
     * Returns the email Sender.
     *
     * @return \cms\email\utility\Sender
     */
    public function sender(): Sender
    {
        return $this->sender;
    }

    /**
     * Send an HTML or plain text email.
     *
     * @param  string $toEmail     The email address to send the email to
     * @param  string $senderName  The name of the sender
     * @param  string $senderEmail The email address of the sender
     * @param  string $subject     The subject of the email
     * @param  string $content     The message to be sent
     * @param  string $format      html or plain
     * @return bool
     */
    public function send(string $toEmail, string $senderName, string $senderEmail, string $subject, string $content, string $format = 'html'): bool
    {
        // Save email to the log
        $logId = $this->log->save($toEmail, $senderName, $senderEmail, $subject, $content, $format);

        // If queuing is enabled add to queue
        if ($this->queue->enabled())
        {
            $this->queue->add($logId);
        }
        else
        {
            $this->sender->send($toEmail, $senderName, $senderEmail, $subject, $content, $format);
        }

        return true;
    }

    /**
     * Load a preset template.
     *
     * @param  string $template Template name
     * @param  array  $vars     Vars to send to the template
     * @return string
     */
    public function preset(string $template, array $vars = [], bool $includeServe = false): string
    {
        $variables = array_merge($this->theme, $vars);

        if ($includeServe)
        {
            $variables['serve'] = Application::instance();
        }

        $filePath  = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $template . '.php';

        return $this->filesystem->ob_read($filePath, $variables);
    }

    /**
     * Load the Serve HTML email template with a custom msg.
     *
     * @param  string $subject The subject of the email
     * @param  string $content The message to be sent
     * @return string
     */
    public function html(string $subject, string $content): string
    {
        $body_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'body.php';

        $_vars =
        [
            'subject' => $subject,
            'content' => $content,
            'logoSrc' => $this->theme['logo_url'],
        ];

        $variables = array_merge($this->theme, $_vars);

        return $this->filesystem->ob_read($body_path, $variables);
    }

    /**
     * Set or get the theme variables.
     *
     * @param  array $theme Assoc array of theme variables (optional) (default [])
     * @return array
     */
    public function theme(array $theme = []): array
    {
        $this->theme = array_merge($this->theme, $theme);

        return $this->theme;
    }

    /**
     * Get a button.
     *
     * @param  string $href URL for the link
     * @param  string $text Text inside the button
     * @param  string $size 'xs'|'sm'|'md'|'lg'|'xl'
     * @return string
     */
    public function button(string $href, string $text, string $size = 'md'): string
    {
        $padding   = $this->theme['btn_size'];
        $gutter    = '&nbsp;&nbsp;&nbsp;&nbsp';
        $font_size = $this->theme['btn_font_size'];

        if ($size === 'xs')
        {
            $padding   = '10px';
            $gutter    = '&nbsp;';
            $font_size = '11px';
        }
        elseif ($size === 'sm')
        {
            $padding   = '13px';
            $gutter    = '&nbsp;&nbsp;';
            $font_size = '12px';
        }
        elseif ($size === 'md')
        {
            $padding   = $this->theme['btn_size'];
            $gutter    = '&nbsp;&nbsp;&nbsp;&nbsp;';
            $font_size = $this->theme['btn_font_size'];
        }
        elseif ($size === 'lg')
        {
            $padding = '20px';
            $gutter  = '&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        elseif ($size === 'xl')
        {
            $padding = '24px';
            $gutter  = '&nbsp;&nbsp;&nbsp;&nbsp;';
        }

        return '
        <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto;">
            <tbody><tr>
                <td style="border-radius: ' . $this->theme['border_radius'] . '; background: ' . $this->theme['btn_bg'] . '; text-align: center;" class="button-td">
                    <a href="' . $href . '" style="background: ' . $this->theme['btn_bg'] . '; border: ' . $padding . ' solid ' . $this->theme['btn_bg'] . '; font-family: sans-serif; font-size: ' . $font_size . '; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: ' . $this->theme['border_radius'] . ';" class="button-a">
                        <span style="color:' . $this->theme['btn_color'] . ';" class="button-link">' . $gutter . strtoupper($text) . $gutter . '</span>
                    </a>
                </td>
            </tr></tbody>
        </table>
        ';
    }
}
