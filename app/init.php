<?php

/**
 * Configure PHP error reporting.
 * @see http://php.net/manual/en/function.error-reporting.php
 */
error_reporting(E_ALL | E_STRICT);

/*
 * Choose if errors that are NOT caught by the Mako error and exception handlers should be
 * printed to the screen as part of the output or if they should be hidden from the user.
 * It is recommended to set this value to false when you are in production.
 */
ini_set('display_errors', true);

/*
 * Override the default path for error logs. Again this is will only be used if
 * error_reporting is enabled. It will also only log errors NOT caught by
 * the Serve error and exception handlers.
 */
ini_set('error_log', __DIR__ . '/storage/logs/' . date('d_m_y') . '_php_errors.log');

/*
 * Convert all errors to ErrorExceptions.
 */
set_error_handler(function ($code, $message, $file, $line)
{
    if((error_reporting() & $code) !== 0)
    {
        throw new ErrorException($message, $code, 0, $file, $line);
    }

    return true;
});

/*
 * Path to the Serve app directory.
 * This is REQUIRED for the application to function
 * properly.
 */
define('SERVE_APPLICATION_PATH', __DIR__);

define('SERVE_CMS_PATH', dirname(__DIR__) . '/src/cms');

putenv('SERVE_ENV=sandbox');

/**
 * Composer autoloader.
 *
 * You need to install composer to use the autoloader
 */
include dirname(__DIR__) . '/vendor/autoload.php';
