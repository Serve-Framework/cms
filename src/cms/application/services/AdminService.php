<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\application\services;

use cms\admin\Admin;
use serve\application\services\Service;

/**
 * Admin access service.
 *
 * @author Joe J. Howard
 */
class AdminService extends Service
{
	/**
	 * {@inheritdoc}
	 */
	public function register(): void
	{
		$this->container->singleton('Admin', function($container)
		{
			return new Admin;
		});
	}
}
