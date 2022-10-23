<?php

return
[
	/*
	 * ---------------------------------------------------------
	 * Error handling
	 * ---------------------------------------------------------
	 *
	 * Application error and exception handling options.
	 */
	'error_handler' =>
	[
		/*
		 * Should we display errors to the end user.
		 * Enable this for debugging only.
		 */
		'display_errors' => true,

		/*
		 * Should we log errors?
		 */
		'log_errors' => true,

		/*
		 * The log path to where Serve will save errors and exceptions to file.
		 * This directory must exist and be writable by PHP.
		 */
		'log_path' => SERVE_APPLICATION_PATH . '/storage/logs',
	],

	/*
	 * ---------------------------------------------------------
	 * Secret
	 * ---------------------------------------------------------
	 *
	 * The secret is used to provide cryptographic signing, and should be set to a unique, unpredictable value.
	 * This is similar to a crypto graphic "salt".
	 * You should NOT use the secret included with the framework in a production environment!
	 */
	'secret' => 'gav9JtvLCangs9EblRKF7jpTobFyjrDdnVgXhgifkHcgW2vuca1141VypG',

	/*
	 * ---------------------------------------------------------
	 * Timezone
	 * ---------------------------------------------------------
	 *
	 * Set the default timezone used by various PHP date functions.
	 * @see http://php.net/manual/en/timezones.php
	 */
	'timezone' => 'Australia/Melbourne',

	/*
	 * ---------------------------------------------------------
	 * Charset
	 * ---------------------------------------------------------
	 *
	 * Default character set used internally in the framework.
	 * @see http://php.net/manual/en/mbstring.supported-encodings.php
	 */
	'charset' => 'UTF-8',

	/*
	 * ---------------------------------------------------------
	 * Send response
	 * ---------------------------------------------------------
	 *
	 * This tells Serve to automatically send the response body,
	 * headers, cookie, session etc.. on all incoming requests.
	 * This is the default behavior for both the framework and the cms.
	 * This means that if the the router matches a route, the
	 * route will change the response object - (e.g send a 200,
	 * throw a 500 etc..), and if a route is not matched a 404
	 * response is sent by default.
	 *
	 * If you disable this, you will need to call
	 * $serve->Response->send() on all requests even on 404s.
	 * You should only disable this if you know what you're doing.
	 *
	 */
	'send_response' => true,

	/*
    	 * ---------------------------------------------------------
    	 * Security
   	 * ---------------------------------------------------------
   	 *
     	 * Security and access settings
    	 */
    	'security' =>
    	[
		/*
		 * ---------------------------------------------------------
		 * Robots.txt search engine/bot indexing
		 * ---------------------------------------------------------
		 *
		 * enable_robots       : Enable/disable bots from access indexing site
		 * robots_text_content : When 'enable_robots' is set to TRUE - the content for the robots.text file.
		 */
		'enable_robots'       => true,
		'robots_text_content' => "User-agent: *\nDisallow: /",

		/*
		 * ---------------------------------------------------------
		 * Ip address blocking
		 * ---------------------------------------------------------
		 *
		 * ip_blocked   : Enable/disable access to the site via ip blocking
		 * ip_whitelist : When 'ip_blocked' is set to TRUE - A list of ip address that are allowed access
		 */
		'ip_blocked'   => false,
		'ip_whitelist' => [],
	],

	/*
	 * ---------------------------------------------------------
	 * Services
	 * ---------------------------------------------------------
	 *
	 * Services to register into the dependency injection container at boot time.
	 * Dependencies will be registered in the the order that they are defined.
	 * If you have your own services that you want to register,
	 * you can put them under the 'app' or any other key you want to use.
	 */
	'services' =>
	[
		/*
		 * Services required for only the core framework. This will
		 * result in the Serve framework with out the CMS.
		 */
		'framework' =>
		[
			'\serve\application\services\framework\SecurityService',
			'\serve\application\services\framework\CacheService',
			'\serve\application\services\framework\HttpService',
			'\serve\application\services\framework\OnionService',
			'\serve\application\services\framework\DatabaseService',
			'\serve\application\services\framework\MVCService',
            '\serve\application\services\framework\PixlService',
			'\serve\application\services\framework\CrawlerService',
			'\serve\application\services\framework\DeploymentService',
			'\serve\application\services\framework\AccessService',
			'\serve\application\services\framework\GatekeeperService',
			'\serve\application\services\framework\EventService',
			'\serve\application\services\framework\AccessService',
		],

		/*
		 * Services required when the application is running in web mode
		 */
		'web' =>
		[
			'\serve\application\services\web\ErrorHandlerService',
		],

		/*
		 * Services required when the application is running in CLI mode
		 */
		'cli' =>
		[
			'\serve\application\services\framework\CliService',
			'\serve\application\services\cli\ErrorHandlerService',
		],

		/*
		 * Defines your own application dependencies here. E.g any thing
		 * you would like loaded into the IoC container and/or booted at runtime.
		 */
		'cms' =>
		[
			'\cms\application\services\WrapperService',
			'\cms\application\services\InstallerService',
			'\cms\application\services\QueryService',
			'\cms\application\services\AdminService',
			'\cms\application\services\AnalyticsService',
			'\cms\application\services\SchemaService',
			'\cms\application\services\ShortcodeService',
			'\cms\application\services\RoutesService',
		],

		/*
		 * Defines your own application dependencies here. E.g any thing
		 * you would like loaded into the IoC container and/or booted at runtime.
		 */
		'app' =>
		[

		],
	],

	/*
	 * ---------------------------------------------------------
	 * CLI Commands
	 * ---------------------------------------------------------
	 *
	 * List of custom CLI command classes
	 */
	'commands' =>
	[

	],

	/*
	 * ---------------------------------------------------------
	 * Application Deployment
	 * ---------------------------------------------------------
	 *
	 * Application deployment and webhook configurations
	 */
	'deployment' =>
	[
		/*
		 * Implementation to use for udating your application.
		 * Default is 'github' which uses weebhooks to update the
		 * repo via git.
		 *
		 * @see https://developer.github.com/webhooks/
		 * @see https://help.github.com/articles/about-webhooks/
		 */
		'implementation' => 'github',

		/*
		 * This token should be provided to you when you setup
		 * your repo weebhooks.
		 *
		 * @see https://developer.github.com/v3/repos/hooks/
		 */
		'token' => 'YOUR_GITHUB_PROVIDED_WEBHOOK_TOKEN',
	],
];
