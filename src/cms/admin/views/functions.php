<?php

use serve\application\Application;

/**
 * Browser caching for assets.
 *
 * @return string
 */
function admin_assets_version()
{
	return time();

	//return Application::VERSION;
}

/**
 * Get the media library.
 *
 * @return string
 */
function admin_media_library(): string
{
	$path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'media-library.php';

	$ACCESS_TOKEN = Application::instance()->Session->token()->get();

	$contents = Application::instance()->View->display($path, ['ACCESS_TOKEN' => $ACCESS_TOKEN]);

	return '<div class="custom-media-lib-wrapper js-triggerable-media">' . $contents . '</div>';
}

/**
 * Enqueue scripts.
 */
function admin_enqueueScripts(): void
{
	$uri = Application::instance()->Request->environment()->REQUEST_URI;

	// Hubble css
	enqueue_style(admin_assets_url() . '/css/hubble.css?v=', admin_assets_version());

	// Theme css
	enqueue_style(admin_assets_url() . '/css/theme.css?v=', admin_assets_version());

	// Dropzone css
	enqueue_style(admin_assets_url() . '/css/vendor/dropzone.css?v=', admin_assets_version());

	// Hubble js
	enqueue_script(admin_assets_url() . '/js/hubble.js?v=', admin_assets_version(), true);

	// Dropzone.js
	enqueue_script(admin_assets_url() . '/js/vendor/dropzone.js?v=', admin_assets_version(), true);

	// theme.js
	enqueue_script(admin_assets_url() . '/js/theme.js?v=', admin_assets_version(), true);

	// Media library .js
	enqueue_script(admin_assets_url() . '/js/media-library.js?v=', admin_assets_version(), true);

	if (str_contains($uri, '/admin/writer/'))
	{
		// Codemirror css
		enqueue_style(admin_assets_url() . '/css/vendor/codemirror.css?v=', admin_assets_version());

		// Highlight css
		enqueue_style(admin_assets_url() . '/css/vendor/highlight.css?v=', admin_assets_version());
		
		// Offline css
		enqueue_style(admin_assets_url() . '/css/vendor/offline.css?v=', admin_assets_version());
			
		// Writer css
		enqueue_style(admin_assets_url() . '/css/writer.css?v=', admin_assets_version());

		// Offline.js
		enqueue_script(admin_assets_url() . '/js/vendor/offline.js?v=', admin_assets_version(), true);

		// Spellcheck.js
		enqueue_script(admin_assets_url() . '/js/vendor/JavaScriptSpellCheck/include.js?v=', admin_assets_version(), true);

		// clipboard.js
		enqueue_script(admin_assets_url() . '/js/vendor/clipboard.js?v=', admin_assets_version(), true);

		// codemirror.js
		enqueue_script(admin_assets_url() . '/js/vendor/codemirror.js?v=', admin_assets_version(), true);

		// highlight.js
		enqueue_script(admin_assets_url() . '/js/vendor/highlight.js?v=', admin_assets_version(), true);

		// markdownIt.js
		enqueue_script(admin_assets_url() . '/js/vendor/markdownIt.js?v=', admin_assets_version(), true);

		// writer.js
		enqueue_script(admin_assets_url() . '/js/writer.js?v=', admin_assets_version(), true);
	}
}

/**
 * Build the sidebar links.
 *
 * @return array
 */
function admin_sirebar_links()
{
	$links =
	[
		'visit' =>
		[
			'link'     => '/" target="blank',
			'text'     => 'Visit Site',
			'icon'     => 'earth2',
			'children' => [],
		],
		'writer' =>
		[
			'link'     => '/admin/writer/',
			'text'     => 'Writer',
			'icon'     => 'pen2',
			'children' => [],
		],
		'content' =>
		[
			'link'     => '/admin/posts/',
			'text'     => 'Content',
			'icon'     => 'paragraph-left2',
			'children' =>
			[
				'posts' =>
				[
					'link'     => '/admin/posts/',
					'text'     => 'Posts',
					'icon'     => 'paragraph-left2',
				],
				'pages' =>
				[
					'link'     => '/admin/pages/',
					'text'     => 'Pages',
					'icon'     => 'file-text3',
				],
				'tags' =>
				[
					'link'     => '/admin/tags/',
					'text'     => 'Tags',
					'icon'     => 'price-tags',
				],
				'categories' =>
				[
					'link'     => '/admin/categories/',
					'text'     => 'Categories',
					'icon'     => 'bookmark2',
					'children' => [],
				],
				'mediaLibrary' =>
				[
					'link'     => '/admin/media/',
					'text'     => 'Media',
					'icon'     => 'camera',
					'children' => [],
				],
			],
		],
		'settings' =>
		[
			'link'     => '/admin/settings/account',
			'text'     => 'Settings',
			'icon'     => 'equalizer',
			'children' => 
			[
				'settingsAccount' =>
				[
					'link'     => '/admin/settings/account/',
					'text'     => 'Account',
					'icon'     => 'vcard',
				],
				'settingsAuthor' =>
				[
					'link'     => '/admin/settings/author/',
					'text'     => 'Author',
					'icon'     => 'address-book',
				],
			],
		],
	];

	$links['settings']['children']['settingsServe'] =
	[
		'link'     => '/admin/settings/serve/',
		'text'     => 'Serve',
		'icon'     => 'cog',
	];
	$links['settings']['children']['settingsAccess'] =
	[
		'link'     => '/admin/settings/access/',
		'text'     => 'Access & Security',
		'icon'     => 'shield',
	];
	$links['settings']['children']['settingsUsers'] =
	[
		'link'     => '/admin/settings/users/',
		'text'     => 'Users',
		'icon'     => 'users',
	];
	$links['settings']['children']['settingsAnalytics'] =
	[
		'link'     => '/admin/settings/analytics/',
		'text'     => 'Analytics',
		'icon'     => 'pie-chart2',
	];
	$links['settings']['children']['settingsTools'] =
	[
		'link'     => '/admin/settings/tools/',
		'text'     => 'Tools',
		'icon'     => 'wrench',
	];
	$links['logs'] =
	[
		'link'     => '/admin/logs/error-logs/',
		'text'     => 'Logs',
		'icon'     => 'terminal',
		'children' =>
		[
			'errorLogs' =>
			[
				'link'     => '/admin/logs/error-logs/',
				'text'     => 'Error Logs',
				'icon'     => 'bug',
			],
			'emailLogs' =>
			[
				'link'     => '/admin/logs/email-logs/',
				'text'     => 'Email Logs',
				'icon'     => 'envelop',
			],
		],
	];

	$links = Application::instance()->Filters->apply('adminSidebar', $links);

	// Logout should always be at the bottom
	$links['logout'] =
	[
		'link'     => '/admin/logout/',
		'text'     => 'Logout',
		'icon'     => 'exit3',
		'children' => [],
	];

	$uri = trim(Application::instance()->Request->environment()->REQUEST_URI, '/');

	foreach($links as $name => $item)
	{
		if (trim($item['link'], '/') === $uri)
		{
			$links[$name]['active'] = true;
		}
		else
		{
			$links[$name]['active'] = false;
		}

		if (isset($item['children']) && !empty($item['children']))
		{
			foreach ($item['children'] as $subName => $subItem)
			{
				if (trim($subItem['link'], '/') === $uri)
				{
					$links[$name]['children'][$subName]['active'] = true;

					$links[$name]['active'] = true;
				}
				else
				{
					$links[$name]['children'][$subName]['active'] = false;
				}
			}
		}
	}

	return $links;
}

/**
 * Get the available post types.
 *
 * @return string
 */
function admin_post_types()
{
	$types =
	[
		'Post' => 'post',
		'Page' => 'page',
	];

	return Application::instance()->Filters->apply('adminPostTypes', $types);
}

/**
 * Is this a dashboard request ?
 *
 * @return bool
 */
function admin_is_dash()
{
	
}

/**
 * Get the assets URL to the admin panel.
 *
 * @return string
 */
function admin_assets_url()
{
	$env = Application::instance()->Request->environment();

	return str_replace($env->DOCUMENT_ROOT, $env->HTTP_HOST, SERVE_CMS_PATH . '/admin/assets');
}

/**
 * Returns a config value.
 *
 * @return mixed
 */
function admin_writer_categories(int $postId): string
{
	$categories = Application::instance()->Query->the_post($postId)->categories;
	$parents    = [];
	$children   = [];

	foreach ($categories as $category)
	{
		$parent = $category->parent();

		if ($parent)
		{
		    $parents[] = $category->name;

		    while ($parent)
		    {
		        $children[] = $parent->name;
		        $parent     = $parent->parent();
		    }
		}
		else
		{
			$parents[] = $category->name;
		}
	}

	return implode(', ', array_unique(array_merge($parents, $children)));
}

/**
 * Formats a price to 2 decimals.
 *
 * @param  mixed  $price
 * @return string
 */
function admin_format_price($price): string
{
	return number_format(floatval($price), 2, '.', '');
}
