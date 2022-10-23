<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\controllers\account;

use cms\admin\controllers\BaseController;

/**
 * Admin panel login controller.
 *
 * @author Joe J. Howard
 */
class Login extends BaseController
{
	/**
	 * {@inheritdoc}
	 */
	protected $template = 'account/login.php';

	/**
	 * {@inheritdoc}
	 */
	protected $pageTitle = 'Login';
}
