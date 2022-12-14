<?php

return
[
    /*
     * ---------------------------------------------------------
     * Admin panel default access
     * ---------------------------------------------------------
     *
     * This is only used after you first install (or reinstall) the CMS
     * to initially access the admin panel.
     *
     * default_username :  Default account username.
     * default_email    :  Default account email address.
     * default_password :  Default account uhashed password.
     * default_name     :  Default account author name.
     */
    'default_username' => 'admin',
    'default_email'    => 'admin@example.com',
    'default_password' => 'password1',
    'default_name'     => 'John Appleseed',

    /*
     * ---------------------------------------------------------
     * Theme options
     * ---------------------------------------------------------
     *
     * themes_path : Directory to where themes are stored.
     * theme_name  : The active theme name (must be the same as its folder).
     */
    'themes_path' => SERVE_APPLICATION_PATH . '/public/themes',
    'theme_name'  => 'serve',

    /*
     * ---------------------------------------------------------
     * Site Meta
     * ---------------------------------------------------------
     *
     * site_title       : The website title.
     * site_description : The meta description.
     */
    'site_title'       => 'Serve',
    'site_description' => 'Serve is a lightweight PHP CMS.',

    /*
     * ---------------------------------------------------------
     * Routing
     * ---------------------------------------------------------
     *
     * sitemap_route     : Route to the XML sitemap.
     * posts_per_page    : Posts per page for pagination.
     * permalinks        : Permalinks structure
     * permalinks_route  : The permalinks route for the router
     * route_tags        : Should Serve route tags e.g example.com/tag/tag-slug/
     * route_categories  : Should Serve route categories e.g example.com/category/category-slug/
     * route_authors     : Should Serve route authors e.g example.com/author/author-slug/
     * route_attachments : Should Serve route attachments e.g example.com/attachment/foobar.png/
     * enable_comments   : Disable or enable comments globally
     */
    'sitemap_route'     => 'sitemap.xml',
    'posts_per_page'    => 9,
    'permalinks'        => 'category/postname/',
    'permalinks_route'  => '(:category)/(:postname)/',
    'blog_location'     => 'blog',
    'route_tags'        => true,
    'route_categories'  => true,
    'route_authors'     => true,
    'route_attachments' => true,
    'enable_comments'   => true,

    /*
     * ---------------------------------------------------------
     * Custom post
     * ---------------------------------------------------------
     *
     * Custom post types for the admin panel
     */
    'custom_posts' => [],

    /*
     * ---------------------------------------------------------
     * Uploads
     * ---------------------------------------------------------
     *
     * Uploading configurations
     */
    'uploads' =>
    [
        /*
         * ---------------------------------------------------------
         * Image thumbnail sizing
         * ---------------------------------------------------------
         *
         * Thumbnail sizes for image uploading via the admin panel.
         * Defining a single number will resize to width. Defining 2 numbers as an
         * array - e.g 'small' => [300, 600] will crop to 300 x 600
         */
        'thumbnail_sizes' =>
        [
            'small'  => 400,
            'medium' => 800,
            'large'  => 1200,
            '16_9'   => [1200, 675],
            '4_3'    => [1200, 900],
            '1_1'    => [1200, 1200],
        ],

        /*
         * ---------------------------------------------------------
         * Uploads path
         * ---------------------------------------------------------
         *
         * Path to media library assets uploaded via the admin panel.
         */
        'path' => SERVE_APPLICATION_PATH . '/public/uploads',

        /*
         * ---------------------------------------------------------
         * Accepted files
         * ---------------------------------------------------------
         *
         * List of accepted mime types for uploading.
         */
        'accepted_mime' =>
        [
            // Images
            'image/jpg',
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/tiff',
            'image/ico',
            'image/vnd.adobe.photoshop',
            'image/webp',
            'image/svg+xml',

            // Microsoft office
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
            'application/vnd.ms-word.document.macroEnabled.12',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
            'application/vnd.ms-excel.sheet.macroEnabled.12',
            'application/vnd.ms-excel.template.macroEnabled.12',
            'application/vnd.ms-excel.addin.macroEnabled.12',
            'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.openxmlformats-officedocument.presentationml.template',
            'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
            'application/vnd.ms-powerpoint.addin.macroEnabled.12',
            'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
            'application/vnd.ms-powerpoint.presentation.macroEnabled.12',

            // Audio
            'audio/aac',
            'application/atom+xml',
            'audio/mpeg',
            'audio/midi',
            'audio/midi',
            'audio/x-matroska',
            'audio/vnd.rn-realaudio',
            'audio/vnd.rn-realaudio',
            'audio/wav',
            'audio/x-ms-wma',
            'audio/ogg',

            // Video
            'video/avi',
            'video/x-flv',
            'video/x-matroska',
            'video/mp4',
            'video/mpeg',
            'video/3gpp',
            'video/3gpp2',

            // Text
            'text/plain',
            'text/xml',
        ],
    ],
];
