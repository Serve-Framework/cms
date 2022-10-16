<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\application\services;

use cms\query\Query;
use serve\application\services\Service;

/**
 * CMS Query.
 *
 * @author Joe J. Howard
 */
class QueryService extends Service
{
	/**
	 * {@inheritdoc}
	 */
	public function register(): void
	{
		$this->container->singleton('Query', function($container)
		{
			return new Query($container);
		});
	}
}
