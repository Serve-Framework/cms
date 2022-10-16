<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\application\services;

use cms\crm\Crm;
use serve\application\services\Service;

/**
 * Crm Service.
 *
 * @author Joe J. Howard
 */
class CrmService extends Service
{
	/**
	 * {@inheritdoc}
	 */
	public function register(): void
	{
		$this->container->singleton('Crm', function($container)
		{
			return new Crm($container->Request, $container->Response, $container->Gatekeeper, $container->LeadProvider, $container->Database->connection()->builder(), $container->Application->isCommandLine(), $container->UserAgent->isCrawler(), $container->Query->is_admin());
		});
	}
}
