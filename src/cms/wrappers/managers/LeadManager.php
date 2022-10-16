<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\wrappers\managers;

use cms\wrappers\providers\LeadProvider;

/**
 * CRM Lead Manager.
 *
 * @author Joe J. Howard
 */
class LeadManager extends Manager
{
    /**
     * {@inheritdoc}
     */
    public function provider(): LeadProvider
	{
        return $this->provider;
	}

	/**
	 * Creates a new tag.
	 *
	 * @param  string $name  Visitor name
	 * @param  string $email Visitor email
	 * @return mixed
	 */
	public function create(string $name, string $email)
	{
		return $this->provider->create(['name' => $name, 'email' => $email, 'last_active' => time()]);
	}

	/**
	 * Gets a visitor by id.
	 *
	 * @param  int   $id Visitor id
	 * @return mixed
	 */
	public function byId(int $id)
	{
		return $this->provider->byId($id);
	}

	/**
	 * Gets a visitor by email.
	 *
	 * @param  string $email Visitor email
	 * @return mixed
	 */
	public function byEmail(string $email)
	{
		return $this->provider->byKey('email', $email, true);
	}
}
