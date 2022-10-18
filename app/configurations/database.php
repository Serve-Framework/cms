<?php

return
[
	/*
	 * ---------------------------------------------------------
	 * Default
	 * ---------------------------------------------------------
	 *
	 * Default configuration to use.
	 */
	'default' => 'serve',

	/*
	 * ---------------------------------------------------------
	 * Configurations
	 * ---------------------------------------------------------
	 *
	 * You can define as many database configurations as you want.
	 *
	 * dsn          : PDO dsn string (will override other connection options if provided)
	 * host         : (optional) Database host
	 * username     : (optional) Connection username
	 * password     : (optional) Connection password
	 * table_prefix : (optional) Prefix for table names
	 * type         : (optional) Database type i.e "mysql" or "sqlite"
	 * options      : (optional) An array of PDO options
	 *
	 * @see https://www.php.net/manual/en/pdo.getattribute.php
	 */
	'configurations' =>
	[
		'serve' =>
		[
			'name'          => 'Serve',
			'host' 	        => 'localhost',
			'username'      => 'root',
			'password'      => 'root',
			'table_prefix'  => 'serve_',
			'type'          => 'mysql',
			'options'       =>
			[
				'MYSQL_ATTR_INIT_COMMAND' => 'SET NAMES utf8',
				'ATTR_DEFAULT_FETCH_MODE' => \PDO::FETCH_ASSOC,
			],
		],
	],
];
