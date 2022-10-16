<?php

use cms\application\Application;

/**
 * Include the application init file.
 */
include __DIR__ . '/app/init.php';

/*
 * Start and run the application.
 */
Application::start(SERVE_APPLICATION_PATH)->run();
