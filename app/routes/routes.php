<?php

/**
 * ---------------------------------------------------------
 * Example routes
 * ---------------------------------------------------------.
 *
 * Examples that come bundled with the framework
 */

/**
 * Example Basic Routing.
 *
 * Uncomment below to see how use the MVC architecture
 */
// $serve->Router->get('/welcome', '\app\controllers\Example@welcome', '\app\models\Example');

/**
 * Example Custom Admin Page.
 *
 * Uncomment below to see how to add a custom page to the Admin Panel.
 */
// $serve->Admin->addPage('My Page', 'my-page', 'superpowers', '\app\models\ExampleAdminPage', APP_DIR.'/views/example-admin-page.php');

/**
 * Example Adding a custom post-type.
 *
 * Uncomment below to see how to add a custom post-type to the Admin Panel.
 */
$serve->Admin->registerPostType('Product', 'product', 'basket', 'products/(:postname)/');