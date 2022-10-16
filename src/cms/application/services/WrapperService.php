<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\application\services;

use cms\wrappers\managers\CategoryManager;
use cms\wrappers\managers\CommentManager;
use cms\wrappers\managers\LeadManager;
use cms\wrappers\managers\MediaManager;
use cms\wrappers\managers\PostManager;
use cms\wrappers\managers\TagManager;
use cms\wrappers\providers\CategoryProvider;
use cms\wrappers\providers\CommentProvider;
use cms\wrappers\providers\LeadProvider;
use cms\wrappers\providers\MediaProvider;
use cms\wrappers\providers\PostProvider;
use cms\wrappers\providers\TagProvider;
use serve\application\services\Service;

/**
 * Database wrapper setup.
 *
 * @author Joe J. Howard
 */
class WrapperService extends Service
{
	/**
	 * {@inheritdoc}
	 */
	public function register(): void
	{
		$this->registerProviders();

		$this->registerManagers();
	}

	/**
	 * Registers the provider managers.
	 */
	private function registerManagers(): void
	{
		$this->container->singleton('CategoryManager', function($container)
		{
			return new CategoryManager($container->Database->connection()->builder(), $container->CategoryProvider);
		});

		$this->container->singleton('TagManager', function($container)
		{
			return new TagManager($container->Database->connection()->builder(), $container->TagProvider);
		});

		$this->container->singleton('PostManager', function($container)
		{
			return new PostManager($container->Database->connection()->builder(), $container->PostProvider);
		});

		$this->container->singleton('MediaManager', function($container)
		{
			return new MediaManager(
				$container->Database->connection()->builder(),
				$container->MediaProvider,
				$container->Request->environment(),
				$container->Gatekeeper,
				$container->Pixl,
				$container->Config->get('cms.uploads.path'),
				$container->Config->get('cms.uploads.accepted_mime'),
				$container->Config->get('cms.uploads.thumbnail_sizes')
			);
		});

		$this->container->singleton('CommentManager', function($container)
		{
			return new CommentManager(
				$container->Database->connection()->builder(),
				$container->CommentProvider,
				$container->Spam,
				$container->Config,
				$container->Request->environment()
			);
		});

		$this->container->singleton('LeadManager', function($container)
		{
			return new LeadManager(
				$container->Database->connection()->builder(),
				$container->LeadProvider
			);
		});
	}

	/**
	 * Registers the wrapper providers.
	 */
	private function registerProviders(): void
	{
		$this->container->singleton('MediaProvider', function($container)
		{
			return new MediaProvider($container->Database->connection()->builder(), $container->Config->get('cms.uploads.thumbnail_sizes'));
		});

		$this->container->singleton('CommentProvider', function($container)
		{
			return new CommentProvider($container->Database->connection()->builder());
		});

		$this->container->singleton('LeadProvider', function($container)
		{
			return new LeadProvider($this->container->Database->connection()->builder());
		});

		$this->container->singleton('TagProvider', function($container)
		{
			return new TagProvider($this->container->Database->connection()->builder());
		});

		$this->container->singleton('CategoryProvider', function($container)
		{
			return new CategoryProvider($this->container->Database->connection()->builder());
		});

		$this->container->singleton('PostProvider', function($container)
		{
			return new PostProvider(
				$container->Database->connection()->builder(),
				$container->Config,
				$container->TagProvider,
				$container->CategoryProvider,
				$container->MediaProvider,
				$container->CommentProvider,
				$container->UserProvider
			);
		});
	}
}
