<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

use cms\admin\controllers\Dashboard as DashController;
use cms\admin\controllers\Accounts as AccountsController;
use cms\admin\models\Accounts as AccountsModel;
use cms\admin\models\Posts as PostsModel;
use cms\admin\models\Tags as TagsModel;
use cms\admin\models\Categories as CategoriesModel;
use cms\admin\models\Comments as CommentsModel;
use cms\admin\models\CommentUsers as CommentUsersModel;
use cms\admin\models\Settings as SettingsModel;
use cms\admin\models\Writer as WriterModel;
use cms\admin\models\MediaLibrary as MediaLibraryModel;
use cms\admin\models\ErrorLogs as ErrorLogsModel;
use cms\admin\models\EmailLogs as EmailLogsModel;
use cms\admin\models\EmailPreview as EmailPreviewModel;
use cms\admin\models\Leads as LeadsModel;
use cms\admin\models\Lead as LeadModel;

/**
 * CMS Application admin routes.
 *
 * @author Joe J. Howard
 */

// Admin login
$router->get('/admin/login/',  AccountsController::class  . '@login', AccountsModel::class);
$router->post('/admin/login/',  AccountsController::class . '@login', AccountsModel::class);

// Admin logout
$router->get('/admin/logout/',  AccountsController::class  . '@logout', AccountsModel::class);
$router->post('/admin/logout/',  AccountsController::class . '@logout', AccountsModel::class);

// Admin forgot pass
$router->get('/admin/forgot-password/',  AccountsController::class  . '@forgotPassword', AccountsModel::class);
$router->post('/admin/forgot-password/',  AccountsController::class . '@forgotPassword', AccountsModel::class);

// Admin forgot username
$router->get('/admin/forgot-username/',  AccountsController::class  . '@forgotUsername', AccountsModel::class);
$router->post('/admin/forgot-username/',  AccountsController::class . '@forgotUsername', AccountsModel::class);

// Admin reset password
$router->get('/admin/reset-password/',  AccountsController::class  . '@resetPassword', AccountsModel::class);
$router->post('/admin/reset-password/',  AccountsController::class . '@resetPassword', AccountsModel::class);

// Admin posts
$router->get('/admin/posts/', DashController::class        . '@posts', PostsModel::class);
$router->get('/admin/posts/(:all)', DashController::class  . '@posts', PostsModel::class);
$router->post('/admin/posts/', DashController::class       . '@posts', PostsModel::class);
$router->post('/admin/posts/(:all)', DashController::class . '@posts', PostsModel::class);

// Admin pages
$router->get('/admin/pages/', DashController::class        . '@pages', PostsModel::class);
$router->get('/admin/pages/(:all)', DashController::class  . '@pages', PostsModel::class);
$router->post('/admin/pages/', DashController::class       . '@pages', PostsModel::class);
$router->post('/admin/pages/(:all)', DashController::class . '@pages', PostsModel::class);

// Admin tags
$router->get('/admin/tags/', DashController::class        . '@tags', TagsModel::class);
$router->get('/admin/tags/(:all)', DashController::class  . '@tags', TagsModel::class);
$router->post('/admin/tags/', DashController::class       . '@tags', TagsModel::class);
$router->post('/admin/tags/(:all)', DashController::class . '@tags', TagsModel::class);

// Admin categories
$router->get('/admin/categories/', DashController::class        . '@categories', CategoriesModel::class);
$router->get('/admin/categories/(:all)', DashController::class  . '@categories', CategoriesModel::class);
$router->post('/admin/categories/', DashController::class       . '@categories', CategoriesModel::class);
$router->post('/admin/categories/(:all)', DashController::class . '@categories', CategoriesModel::class);

// Admin comments
$router->get('/admin/comments/', DashController::class        . '@comments', CommentsModel::class);
$router->get('/admin/comments/(:all)', DashController::class  . '@comments', CommentsModel::class);
$router->post('/admin/comments/', DashController::class       . '@comments', CommentsModel::class);
$router->post('/admin/comments/(:all)', DashController::class . '@comments', CommentsModel::class);

// Admin comment authors
$router->get('/admin/comment-users/', DashController::class         . '@commentUsers', CommentUsersModel::class);
$router->get('/admin/comment-users/(:all)', DashController::class   . '@commentUsers', CommentUsersModel::class);
$router->post('/admin/comment-users/', DashController::class        . '@commentUsers', CommentUsersModel::class);
$router->post('/admin/comments-users/(:all)', DashController::class . '@commentUsers', CommentUsersModel::class);

// Admin settings
$router->get('/admin/settings/', DashController::class  . '@settings', SettingsModel::class);
$router->post('/admin/settings/', DashController::class . '@settings', SettingsModel::class);

// Admin account settings
$router->get('/admin/settings/account/', DashController::class  . '@settingsAccount', SettingsModel::class);
$router->post('/admin/settings/account/', DashController::class . '@settingsAccount', SettingsModel::class);

// Admin author settings
$router->get('/admin/settings/author/', DashController::class  . '@settingsAuthor', SettingsModel::class);
$router->post('/admin/settings/author/', DashController::class . '@settingsAuthor', SettingsModel::class);

// Admin serve settings
$router->get('/admin/settings/serve/', DashController::class  . '@settingsServe', SettingsModel::class);
$router->post('/admin/settings/serve/', DashController::class . '@settingsServe', SettingsModel::class);

// Admin access settings
$router->get('/admin/settings/access/', DashController::class  . '@settingsAccess', SettingsModel::class);
$router->post('/admin/settings/access/', DashController::class . '@settingsAccess', SettingsModel::class);

// Admin serve users
$router->get('/admin/settings/users/', DashController::class . '@settingsUsers', SettingsModel::class);
$router->post('/admin/settings/users/', DashController::class . '@settingsUsers', SettingsModel::class);

// Admin serve tools
$router->get('/admin/settings/tools/', DashController::class . '@settingsTools', SettingsModel::class);
$router->post('/admin/settings/tools/', DashController::class . '@settingsTools', SettingsModel::class);

// Admin analytics
$router->get('/admin/settings/analytics/', DashController::class . '@settingsAnalytics', SettingsModel::class);
$router->post('/admin/settings/analytics/', DashController::class . '@settingsAnalytics', SettingsModel::class);

// Admin writer
$router->get('/admin/writer/', DashController::class        . '@writer', WriterModel::class);
$router->get('/admin/writer/(:all)', DashController::class  . '@writer', WriterModel::class);
$router->post('/admin/writer/', DashController::class       . '@writer', WriterModel::class);
$router->post('/admin/writer/(:any)', DashController::class . '@writer', WriterModel::class);

// Admin media
$router->get('/admin/media/', DashController::class          . '@mediaLibrary', MediaLibraryModel::class);
$router->post('/admin/media-library/', DashController::class . '@mediaLibrary', MediaLibraryModel::class);

// Admin error logs
$router->get('/admin/logs/error-logs/', DashController::class  . '@errorLogs', ErrorLogsModel::class);
$router->post('/admin/logs/error-logs/', DashController::class . '@errorLogs', ErrorLogsModel::class);

// Admin email logs
$router->get('/admin/logs/email-logs/', DashController::class  . '@emailLogs', EmailLogsModel::class);
$router->post('/admin/logs/email-logs/', DashController::class . '@emailLogs', EmailLogsModel::class);

// Admin email preview
$router->get('/admin/email-preview/(:any)/', DashController::class . '@emailPreview', EmailPreviewModel::class);

// Admin leads
$router->get('/admin/leads/', DashController::class . '@leads', LeadsModel::class);

// Admin lead
$router->get('/admin/leads/(:any)', DashController::class . '@lead', LeadsModel::class);
