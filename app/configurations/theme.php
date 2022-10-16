<?php

return
[
	/*
     * ---------------------------------------------------------
     * Navigation menus
     * ---------------------------------------------------------
     */
	'navigation' =>
	[
		'site' => [
			[
				'href'  => '/blog/',
				'title' => 'Energy, Training, Bodyweight, Wellness, Workouts, Exercises, Nutrition and More',
				'text'  => 'Blog',
			],
		],
		'blog' => [

			[
				'href'  => '/blog/',
				'title' => 'Back To Serve Blog',
				'text'  => 'Blog',
			],
			[
				'href'  => '/blog/category/health/',
				'title' => 'Healthy Natural Living Tips and Professional Advice',
				'text'  => 'Health',
			],
			[
				'href'  => '/blog/category/nutrition/',
				'title' => 'Natural Nutrition Plans, Tips, Recipes and More',
				'text'  => 'Nutrition',
			],
			[
				'href'  => '/blog/category/nutrition/recipes/',
				'title' => 'Health and Nutrition Recipes',
				'text'  => 'Recipes',
			],
			[
				'href'  => '/',
				'title' => 'Back To Serve Homepage',
				'text'  => 'Serve',
			],
		],
	],

	/*
     * ---------------------------------------------------------
     * Homepage sliders
     * ---------------------------------------------------------
     */
	'homepage' =>
	[
		'carousel'       => [],
		'mobile_banners' => [],
	],

	/*
     * ---------------------------------------------------------
     * Campaign monitor credentials
     * ---------------------------------------------------------
     */
	'campaign_monitor' =>
	[
	    'api_key' => 'e5806ad375052e5480fc7623316f526a',
        'list_id' => '2920d9c7989cd93d12d17c3276bd2239',
	],

	/*
     * ---------------------------------------------------------
     * Social media accounts
     * ---------------------------------------------------------
     */
	'social' =>
	[
		'instagram'      => '',
		'twitter'        => '',
		'facebook'       => '',
		'twitter_handle' => '',
	],

	/*
     * ---------------------------------------------------------
     * Email addresses
     * ---------------------------------------------------------
     */
	'emails' =>
	[
		'info' => 'info@example.com',
	],
];
