<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\controllers;

use serve\mvc\controller\Controller;

/**
 * Add new comment controller.
 *
 * @author Joe J. Howard
 */
class Comments extends Controller
{
	/**
	 * Dispatch the request.
	 */
	public function addComment()
	{
		if ($this->Request->isAjax())
		{
			$status = $this->model->validate();

			if ($status)
			{
				return $this->jsonResponse(['details' => $status]);
			}
		}

		$this->notFoundResponse();
	}
}
