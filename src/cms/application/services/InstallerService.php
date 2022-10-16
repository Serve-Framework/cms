<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\application\services;

use cms\install\Installer;
use serve\application\services\Service;

/**
 * CMS Installer.
 *
 * @author Joe J. Howard
 */
class InstallerService extends Service
{
	/**
	 * {@inheritdoc}
	 */
	public function register(): void
	{
		$this->container->singleton('Installer', function($container)
		{
			return new Installer($container->Config, $container->Database, $container->Access);
		});
	}
}
