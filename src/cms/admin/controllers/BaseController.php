<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\controllers;

use serve\mvc\controller\Controller;

/**
 * Admin panel base controller.
 *
 * @author Joe J. Howard
 */
abstract class BaseController extends Controller
{
	/**
	 * The page template to load.
	 *
	 * @var string
	 */
	protected $template;

	/**
	 * The admin request type/name
	 *
	 * @var string
	 */
	protected $pageTitle;

	/**
	 * Variables to be passed to the view.
	 *
	 * @var array
	 */
	protected $viewVars = [];

	/**
	 * Dispatch the request.
	 */
	public function dispatch(): void
	{
		// Disabled HTTP caching
		$this->Response->disableCaching();

		// Disabled the CDN
		$this->Response->CDN()->disable();

		// Make sure this is a valid Ajax request
		if ($this->Request->isAjax())
		{
			$this->Response->format()->set('application/json');

			$this->Response->body()->clear();

			$onAJAX = [$this->model, 'onAJAX'];

			if (is_callable($onAJAX))
			{
				$response = call_user_func($onAJAX);

				if ($response !== false)
				{
					$this->Response->status()->set(200);

					$this->Response->body()->set(json_encode(['response' => $response]));

					return;
				}
			}

			$this->Response->notFound();

			return;
		}

		// Regular POST requests
		elseif ($this->Request->isPost())
		{
			$onPOST = [$this->model, 'onPOST'];

			if (is_callable($onPOST))
			{
				$response = call_user_func($onPOST);

				if ($response === false)
				{
					$this->Response->notFound();

					return;
				}

				$this->viewVars['POST_RESPONSE'] = $response;
			}
		}

		$onGET = [$this->model, 'onGET'];

		if (is_callable($onGET))
		{
			$response = call_user_func($onGET);

			if ($response === false || !is_array($response))
			{
				$this->Response->notFound();

				return;
			}

			$this->viewVars = array_merge($this->viewVars, $response);

			$this->render();

			return;
		}

		$this->Response->notFound();
	}

    /**
     * Render the admin panel.
     */
    protected function render(): void
    {
    	$vars = $this->viewVars;

    	$vars['USER'] = $this->Gatekeeper->getUser();

    	$vars['ADMIN_PAGE_TEMPLATE'] = $this->template;

    	$vars['ADMIN_PAGE_TITLE'] = $this->pageTitle;

    	$vars['ACCESS_TOKEN'] = $this->Response->session()->token()->get();

        $template = dirname(__DIR__) . '/views/admin.php';

        $this->Response->body()->set($this->Response->view()->display($template, $vars));
    }
}
