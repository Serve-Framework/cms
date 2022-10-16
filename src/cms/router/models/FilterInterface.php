<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\models;

/**
 * Query filter interface.
 *
 * @author Joe J. Howard
 */
interface FilterInterface
{
	/**
	 * Filters the posts.
	 *
	 * @return bool
	 */
	public function filter(): bool;

	/**
	 * Get the request Type.
	 *
	 * @return string
	 */
	public function requestType(): string;
}
