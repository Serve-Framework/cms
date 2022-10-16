<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\controllers;

use Closure;
use serve\mvc\controller\Controller;
use serve\http\request\Request;
use serve\http\response\Response;
use cms\rss\Feed;

/**
 * CMS Query Dispatcher.
 *
 * @author Joe J. Howard
 */
class Content extends Controller
{
	/**
     * Constructor.
     *
     * @param \serve\http\request\Request   $request     Request instance
     * @param \serve\http\response\Response $response    Response instance
     * @param \Closure                                $next        Next middleware closure
     * @param string                                  $requestType The custom post type
     * @param string                                  $modelClass  The model class to load
     */
    public function __construct(Request $request, Response $response, Closure $next, string $modelClass, ?string $postType = null)
    {
        $this->nextMiddleware = $next;

        $this->loadModel($modelClass);

        if ($postType)
        {
        	$this->model->setRequestType($postType);
        }
    }

    /**
     * Apply route to filter posts and load theme templates.
     */
    public function apply(): void
    {
    	$requestType = $this->model->requestType();
    	$filter      = $this->model->filter();
    
    	if ($filter)
    	{
    		$this->Response->status()->set(200);
    	}
    	else
    	{
    		$this->Response->status()->set(404);
    	}

    	$template = $this->getTemplate($requestType);

		if ($filter && $template)
		{
			if ($requestType !== 'page' && $requestType !== 'single')
			{
				$this->Response->disableCaching();
			}

			// Set the_post so we're looking at the first item
	        if (isset($this->Query->posts[0]))
	        {
	            $this->Query->post = $this->Query->posts[0];
	        }

			$this->fileResponse($template);
		}
		else
		{
			$this->Query->reset();

			$this->nextMiddleware();
		}
    }

    /**
     * Loads an RSS route.
     */
    public function rss(): void
    {
        if ($this->model->filter())
        {
            $format = explode('/', $this->Request->environment()->REQUEST_PATH);

            $rss = new Feed($this->Request, $this->Response, array_pop($format));

            $rss->render();
        }
        else
        {
            $this->nextMiddleware();
        }
    }

	/**
	 * Determine what template to use.
	 *
	 * @return string|false
	 */
	private function getTemplate(string $requestType)
	{
		$themeDir = $this->Config->get('cms.themes_path') . '/' . $this->Config->get('cms.theme_name');

		// Waterfall of pages
		$waterfall =  [];

		// Explode request url
		$urlParts = explode('/', $this->Request->environment()->REQUEST_PATH);

		// 404s never get a template
		if ($this->Response->status()->get() === 404)
		{
			return false;
		}

		if ($requestType === 'home')
		{
			$waterfall[] = 'homepage';
			$waterfall[] = 'index';
		}
		elseif ($requestType === 'home-page')
		{
			$waterfall[] = 'home-' . array_shift($urlParts);
			$waterfall[] = 'blog';
			$waterfall[] = 'index';
		}
		elseif (str_contains($requestType, 'home-'))
		{
			$waterfall[] = 'home-' . array_shift($urlParts);
			$waterfall[] = $requestType;
		}
		elseif ($requestType === 'page')
		{
			$waterfall[] = 'page-' . array_shift($urlParts);
			$waterfall[] = 'page';
		}
		elseif ($requestType === 'single')
		{
			$waterfall[] = 'single-' . array_pop($urlParts);
			$waterfall[] = 'single';
		}
		elseif (str_contains($requestType, 'single-'))
		{
			$waterfall[] = $requestType . '-' . array_pop($urlParts);
			$waterfall[] = $requestType;
			$waterfall[] = 'single';
		}
		elseif ($requestType === 'tag')
		{
			if ($this->Query->the_taxonomy())
			{
				$waterfall[] = 'tag-' . $this->Query->the_taxonomy()->slug;
			}

			$waterfall[] = 'taxonomy-tag';
			$waterfall[] = 'tag';
			$waterfall[] = 'taxonomy';
		}
		elseif ($requestType === 'category')
		{
			if ($this->Query->the_taxonomy())
			{
				$waterfall[] = 'category-' . $this->Query->the_taxonomy()->slug;
			}

			$waterfall[] = 'taxonomy-category';
			$waterfall[] = 'category';
			$waterfall[] = 'taxonomy';
		}
		elseif ($requestType === 'author')
		{
			if ($this->Query->the_taxonomy())
			{
				$waterfall[] = 'author-' . $this->Query->the_taxonomy()->slug;
			}
			$waterfall[] = 'taxonomy-author';
			$waterfall[] = 'author';
			$waterfall[] = 'taxonomy';
		}
		elseif ($requestType === 'search')
		{
			$waterfall[] = 'search';
			$waterfall[] = 'index';
		}
		elseif ($requestType === 'attachment')
		{
			$waterfall[] = 'attachment-' . array_pop($urlParts);
			$waterfall[] = 'attachment';
		}

		foreach ($waterfall as $name)
		{
			$template = "{$themeDir}/$name.php";
			
			if (file_exists($template))
			{
				return $template;
			}
		}

		if ($requestType === 'attachment')
		{
			$template = SERVE_CMS_PATH . '/router/views/attachment.php';

			if (file_exists($template))
			{
				return $template;
			}
		}

		if (file_exists("{$themeDir}/$requestType.php"))
		{
			return "{$themeDir}/$requestType.php";
		}

		return false;
	}
}
