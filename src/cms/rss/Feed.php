<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\rss;

use serve\http\request\Request;
use serve\http\response\Response;
use serve\utility\Mime;

/**
 * RSS feeds.
 *
 * @author Joe J. Howard
 */
class Feed
{
	/**
	 * Request object.
	 *
	 * @var \serve\http\request\Request
	 */
	private $request;

	/**
	 * Response object.
	 *
	 * @var \serve\http\response\Response
	 */
	private $response;

	/**
	 * RSS format to load.
	 *
	 * @var string
	 */
	private $format;

    /**
     * Constructor.
     *
     * @param \serve\http\request\Request   $request  Request object
     * @param \serve\http\response\Response $response Response object
     * @param string                                  $format   RSS format 'rss'||'atom'||'rdf' (optional) (default 'rss')
     */
    public function __construct(Request $request, Response $response, string $format = 'rss')
    {
    	$format = $format === 'feed' ? 'rss' : $format;

        $this->request = $request;

        $this->response = $response;

        $this->format = $format;
    }

	/**
	 * Render the XML into the HTPP response.
	 */
	public function render(): void
	{
		// Set appropriate content type header
        $this->response->format()->set(Mime::fromExt($this->format) . ', application/xml');

        // Set the response body
        $this->response->body()->set($this->xml());

        // Set the status
        $this->response->status()->set(200);

        // Disable the cache
        $this->response->disableCaching();
	}

	/**
	 * Load an RSS XML feed.
	 *
	 * @return string
	 */
	private function xml(): string
	{
		$XML = $this->response->view()->display($this->template('head'));

		$XML .= $this->response->view()->display($this->template('posts'));

		$XML .= $this->response->view()->display($this->template('footer'));

		return $XML;
	}

	/**
	 * Load an RSS template file.
	 *
	 * @param  string $name The name of the template to load
	 * @return string
	 */
	private function template(string $name): string
	{
		return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . strtolower($this->format) . DIRECTORY_SEPARATOR . $name . '.php';
	}
}
