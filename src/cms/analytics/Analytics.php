<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\analytics;

use serve\mvc\model\Model;
use serve\utility\Money;

/**
 * Google/Facebook Analytics Utility.
 *
 * @author Joe J. Howard
 */
class Analytics extends Model
{
    /**
     * Is Google analytics enabled.
     *
     * @var bool
     */
    private $gAnalyticsEnabled;

    /**
     * Is Google adwords enabled.
     *
     * @var bool
     */
    private $adwordsEnabled;

    /**
     * Is Facebook pixel enabled.
     *
     * @var bool
     */
    private $fbEnabled;

    /**
     * Google analytics tracking id.
     *
     * @var string
     */
    private $gAnalyticsId;

    /**
     * Google adwords tracking id.
     *
     * @var string
     */
    private $gAdwordsId;

    /**
     * Google adwords conversion id.
     *
     * @var string
     */
    private $googleAwCvId;

    /**
     * Facebook pixel tracking id.
     *
     * @var string
     */
    private $fbPixelId;

    /**
     * Constructor.
     *
     * @param bool   $gAnalyticsEnabled Enable or disable google analytics
     * @param string $gAnalyticsId      Google analytics tracking id
     * @param bool   $adwordsEnabled    Enable or disable google adwords
     * @param string $gAdwordsId        Google adwords tracking id
     * @param string $googleAwCvId      Google adwords conversion id
     * @param bool   $fbEnabled         Enable or disable fb pixel
     * @param string $fbPixelId         Facebook pixel tracking id
     */
    public function __construct(bool $gAnalyticsEnabled, string $gAnalyticsId, bool $adwordsEnabled, string $gAdwordsId, string $googleAwCvId, bool $fbEnabled, string $fbPixelId)
    {
        $this->gAnalyticsEnabled = $gAnalyticsEnabled;

        $this->gAnalyticsId = $gAnalyticsId;

        $this->adwordsEnabled = $adwordsEnabled;

        $this->gAdwordsId = $gAdwordsId;

        $this->googleAwCvId = $googleAwCvId;

        $this->fbEnabled = $fbEnabled;

        $this->fbPixelId = $fbPixelId;
    }

    /**
     * Get the main Google Analytics tracking code.
     *
     * @return string
     */
    public function googleTrackingCode(): string
    {
        return $this->cleanWhiteSpace("
        <script async src=\"https://www.googletagmanager.com/gtag/js?id=UA-119334451-1\"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '" . $this->gAnalyticsId . "');
            gtag('config', '" . $this->gAdwordsId . "');
            " . $this->googleUserData() . "
            gtag('event', 'page_view', {'send_to': '" . $this->gAdwordsId . "'} );
        </script>");
    }

    /**
     * Get the main Facebook tracking code.
     *
     * @return string
     */
    public function facebookTrackingCode(): string
    {
        return $this->cleanWhiteSpace("
        <script type=\"text/javascript\">
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '" . $this->fbPixelId . "', " . $this->facebookUserData() . ");
            fbq('track', 'PageView');
        </script><noscript><img height=\"1\" width=\"1\" style=\"display:none\" src=\"https://www.facebook.com/tr?id=" . $this->fbPixelId . '&ev=PageView&noscript=1"/></noscript>');
    }

    /**
     * Get google user id.
     *
     * @return string
     */
    private function googleUserData(): string
    {
        if ($this->Gatekeeper->isLoggedIn())
        {
            return "gtag('set', {'user_id': '" . $this->Gatekeeper->getUser()->id . "'});";
        }

        return '';
    }

    /**
     * Get facebook user data.
     *
     * @return string
     */
    private function facebookUserData(): string
    {
        if ($this->Gatekeeper->isLoggedIn())
        {
            $names     = explode(' ', $this->Gatekeeper->getUser()->name);
            $firstname = trim(ucwords(array_shift($names)));
            $lastname  = trim(implode(' ', $names));
            $email     = $this->Gatekeeper->getUser()->email;
            $fbUser    =
            [
                'em' => $email,
                'fn' => $firstname,
                'ln' => $lastname,
            ];

            return json_encode($fbUser);
        }
        else
        {
            return '{}';
        }
    }

    /**
     * Format HTML nicely.
     *
     * @param  string $html HTML to format
     * @return string
     */
    private function fbq(string $event, array $eventObj): string
    {
        $script  = '<script type="text/javascript">' . PHP_EOL;
        $script .= 'fbq(\'track\', \'' . $event . '\',  ' . PHP_EOL;
        $script .= str_replace('\u003E', '>', json_encode($eventObj, JSON_PRETTY_PRINT)) . ');' . PHP_EOL;
        $script .= '</script>' . PHP_EOL;

        return $script;
    }

    /**
     * Format HTML nicely.
     *
     * @param  string $html HTML to format
     * @return string
     */
    private function gtag(string $event, array $eventObj): string
    {
        $script  = '<script type="text/javascript">' . PHP_EOL;
        $script .= 'gtag(\'event\', \'' . $event . '\',  ' . PHP_EOL;
        $script .= str_replace('\u003E', '>', json_encode($eventObj, JSON_PRETTY_PRINT)) . ');' . PHP_EOL;
        $script .= '</script>' . PHP_EOL;

        return $script;
    }

    /**
     * Format HTML nicely.
     *
     * @param  string $html HTML to format
     * @return string
     */
    private function cleanWhiteSpace(string $html)
    {
        return trim(preg_replace('/\t+/', '', $html)) . "\n";
    }
}
