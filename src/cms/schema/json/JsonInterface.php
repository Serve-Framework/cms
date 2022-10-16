<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\schema\json;

/**
 * Json generator interface.
 *
 * @author Joe J. Howard
 */
interface JsonInterface
{
	/**
	 * Generate the JSON data for this component.
	 *
	 * @return array
	 */
	public function generate(): array;
}
