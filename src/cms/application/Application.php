<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/Framework/blob/master/LICENSE
 */

namespace cms\application;

use serve\application\web\Application as BaseApplication;
use serve\http\response\exceptions\NotFoundException;

/**
 * CMS application.
 *
 * @author Joe J. Howard
 */
class Application extends BaseApplication
{
	/**
     * Run the application.
     */
    public function run(): void
    {
    	$this->bootCms();

        $this->precheckAccess();

        $this->container->Router->dispatch();

        $this->container->Onion->peel();

        $this->container->Response->send();

        $this->container->ErrorHandler->restore();
    }

    /**
     * Boot the CMS.
     */
    protected function bootCms(): void
    {
    	$this->registerPackage('cms');
    	
    	$this->registerViewIncludes();

    	$this->bootInstaller();

		$this->notFoundHandling();
		
		if ($this->container->Installer->isInstalled())
		{
			$this->applyRoutes();
		}
    }

	/**
	 * Validate the incoming request with the access conditions.
	 */
	protected function precheckAccess(): void
	{
		if ($this->container->Access->ipBlockEnabled() && !$this->container->Access->isIpAllowed())
		{
			$this->container->Access->block();
		}
	}

	/**
	 * Apply the CMS routes.
	 */
	protected function applyRoutes(): void
	{
		include_once dirname(__DIR__) . '/router/Routes.php';
	}

    /**
     * Registers includes on all view renders.
     */
    protected function registerViewIncludes(): void
    {
    	$themeFuncs = $this->container->Config->get('cms.themes_path') . '/' . $this->container->Config->get('cms.theme_name') . '/functions.php';

    	$queryFuncs = dirname(__DIR__) . '/query/Includes.php';
    		
    	$this->container->View->includes([ $themeFuncs, $queryFuncs ]);
    }

    /**
     * Boot the installer.
     */
    protected function bootInstaller(): void
    {
    	// Make sure Serve CMS is installed
		if (!$this->container->Installer->isInstalled())
		{
			$this->container->Router->get('/', [&$this->container->Installer, 'run']);

			$this->container->Router->get('/', [&$this->container->Installer, 'display']);
		}
    }

	/**
	 * Handle 404 not found on for the CMS.
	 */
	protected function notFoundHandling(): void
	{
		// 404 get displayed the theme 404 template
		$this->container->ErrorHandler->handle(NotFoundException::class, function($exception): void
		{
			// Only show the template if it exists, not ajax request and not displaying errors
			// Otherwise we fallback to applications default error handling
			$template = $this->container->Config->get('cms.themes_path') . DIRECTORY_SEPARATOR . $this->container->Config->get('cms.theme_name') . DIRECTORY_SEPARATOR . '404.php';

			if (file_exists($template) && !$this->container->Request->isAjax() && !$this->container->ErrorHandler->display_errors())
			{
				$this->container->Response->status()->set(404);

				$this->container->Response->body()->set($this->container->View->display($template));

				$this->container->Response->send();

				// Stop handling this error
				// return false;
			}

		});
	}
}
