<?php

namespace cms\admin\models;

use serve\common\SqlBuilderTrait;
use serve\mvc\model\Model;

/**
 * Model base class.
 *
 * @author Joe J. Howard
 */
abstract class BaseModel extends Model
{
	use SqlBuilderTrait;

	/**
	 * POST variables.
	 *
	 * @var array
	 */
	protected $post;

	/**
     * Constructor.
     */
    public function __construct()
    {
    	$this->post = $this->Request->fetch();
    }

	/**
	 * On HTTP POST.
	 * 
	 * @return array|false
	 */
	abstract public function onPOST() : array|false;

	/**
	 * On HTTP AJAX.
	 * 
	 * @return array|false
	 */
	abstract public function onAJAX() : array|false|string;

	/**
	 * On HTTP GET.
	 * 
	 * @return array|false
	 */
	abstract public function onGET() : array|false;

	/**
	 * Is the current client logged in ?
	 *
	 * @return bool
	 */
	protected function isLoggedIn(): bool
	{
		return $this->Gatekeeper->isLoggedIn() && $this->Gatekeeper->isAdmin();
	}

	/**
	 * Returns the values required to display a POST
	 * response message.
	 *
	 * @param  string $class HTML message classname
	 * @param  string $msg   Text to go inside the message element
	 * @return array
	 */
	protected function postMessage(string $class, string $msg): array
	{
		$icon = '';

		if ($class === 'danger')
		{
			$icon = 'cross2';
		}
		elseif ($class === 'success')
		{
			$icon = 'checkmark';
		}
		elseif ($class === 'info')
		{
			$icon = 'bell';
		}
		elseif ($class === 'warning')
		{
			$icon = 'power';
		}

		return
		[
			'class' => $class,
			'icon'  => $icon,
			'msg'   => $msg,
		];
	}
}
