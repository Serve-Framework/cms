<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\application\services;

use cms\shortcode\Shortcodes;
use serve\application\services\Service;

/**
 * Crm Service.
 *
 * @author Joe J. Howard
 */
class ShortcodeService extends Service
{
	/**
	 * {@inheritdoc}
	 */
	public function register(): void
	{
		$this->container->singleton('Shortcodes', function()
		{
			return new Shortcodes;
		});
	}
}
