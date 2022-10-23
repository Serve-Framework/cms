<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\routes;

use cms\admin\controllers\Dashboard as DashController;
use cms\admin\controllers\account\Login as LoginController;
use cms\admin\controllers\account\Logout as LogoutController;
use cms\admin\controllers\account\ForgotPassword as ForgotPasswordController;
use cms\admin\controllers\account\ForgotUsername as ForgotUsernameController;
use cms\admin\controllers\account\ResetPassword as ResetPasswordController;
use cms\admin\controllers\dash\Posts as PostsController;
use cms\admin\controllers\dash\Pages as PagesController;
use cms\admin\controllers\dash\Tags as TagsController;
use cms\admin\controllers\dash\Categories as CategoriesController;
use cms\admin\controllers\dash\Comments as CommentsController;
use cms\admin\controllers\dash\CommentUsers as CommentUsersController;
use cms\admin\controllers\dash\Settings as SettingsController;
use cms\admin\controllers\dash\Writer as WriterController;
use cms\admin\controllers\dash\MediaLibrary as MediaLibraryController;
use cms\admin\controllers\dash\EmailLogs as EmailLogsController;
use cms\admin\controllers\dash\ErrorLogs as ErrorLogsController;
use cms\admin\controllers\dash\EmailPreview as EmailPreviewController;
use cms\admin\models\account\Login as LoginModel;
use cms\admin\models\account\Logout as LogoutModel;
use cms\admin\models\account\ForgotPassword as ForgotPasswordModel;
use cms\admin\models\account\ForgotUsername as ForgotUsernameModel;
use cms\admin\models\account\ResetPassword as ResetPasswordModel;
use cms\admin\models\dash\Posts as PostsModel;
use cms\admin\models\dash\Tags as TagsModel;
use cms\admin\models\dash\Categories as CategoriesModel;
use cms\admin\models\dash\Comments as CommentsModel;
use cms\admin\models\dash\CommentUsers as CommentUsersModel;
use cms\admin\models\dash\Settings as SettingsModel;
use cms\admin\models\dash\Writer as WriterModel;
use cms\admin\models\dash\MediaLibrary as MediaLibraryModel;
use cms\admin\models\dash\ErrorLogs as ErrorLogsModel;
use cms\admin\models\dash\EmailLogs as EmailLogsModel;
use cms\admin\models\dash\EmailPreview as EmailPreviewModel;

/**
 * CMS admin routes.
 *
 * @author Joe J. Howard
 */
class Admin extends RoutesBase
{
    /**
     * {@inheritdoc}
     */
    protected $routes =
    [
        // Admin login
        [
            'method'     => 'get',
            'route'      => '/admin/login/',
            'controller' =>  LoginController::class . '@dispatch',
            'model'      => LoginModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/login/',
            'controller' => LoginController::class . '@dispatch',
            'model'      => LoginModel::class,
        ],

        // Admin logout
        [
            'method'     => 'get',
            'route'      => '/admin/logout/',
            'controller' => LogoutController::class . '@dispatch',
            'model'      => LogoutModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/logout/',
            'controller' => LogoutController::class . '@dispatch',
            'model'      => LogoutModel::class,
        ],

        // Admin forgot pass
        [
            'method'     => 'get',
            'route'      => '/admin/forgot-password/',
            'controller' => ForgotPasswordController::class . '@dispatch',
            'model'      => ForgotPasswordModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/forgot-password/',
            'controller' => ForgotPasswordController::class . '@dispatch',
            'model'      => ForgotPasswordModel::class,
        ],

        // Admin forgot username
        [
            'method'     => 'get',
            'route'      => '/admin/forgot-username/',
            'controller' => ForgotUsernameController::class . '@dispatch',
            'model'      => ForgotUsernameModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/forgot-username/',
            'controller' => ForgotUsernameController::class . '@dispatch',
            'model'      => ForgotUsernameModel::class,
        ],

        // Admin reset password
        [
            'method'     => 'get',
            'route'      => '/admin/reset-password/',
            'controller' => ResetPasswordController::class . '@dispatch',
            'model'      => ResetPasswordModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/reset-password/',
            'controller' => ResetPasswordController::class . '@dispatch',
            'model'      => ResetPasswordModel::class,
        ],

        // Admin posts
        [
            'method'     => 'get',
            'route'      => '/admin/posts/',
            'controller' => PostsController::class . '@dispatch',
            'model'      => PostsModel::class,
        ],
        [
            'method'     => 'get',
            'route'      => '/admin/posts/(:all)',
            'controller' => PostsController::class . '@dispatch',
            'model'      => PostsModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/posts/',
            'controller' => PostsController::class . '@dispatch',
            'model'      => PostsModel::class,
        ],
        [
            'method'      => 'post',
            'route'       => '/admin/posts/(:all)',
            'controller'  => PostsController::class . '@dispatch',
            'model'       => PostsModel::class,
        ],

        // Admin pages
        [
            'method'     => 'get',
            'route'      => '/admin/pages/',
            'controller' => PagesController::class . '@dispatch',
            'model'      => PostsModel::class,
        ],
        [
            'method'     => 'get',
            'route'      => '/admin/pages/(:all)',
            'controller' => PagesController::class . '@dispatch',
            'model'      => PostsModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/pages/',
            'controller' => PagesController::class . '@dispatch',
            'model'      => PostsModel::class,
        ],
        [
            'method'      => 'post',
            'route'       => '/admin/pages/(:all)',
            'controller'  => PagesController::class . '@dispatch',
            'model'       => PostsModel::class,
        ],

        // Admin tags
        [
            'method'     => 'get',
            'route'      => '/admin/tags/',
            'controller' => TagsController::class . '@dispatch',
            'model'      => TagsModel::class,
        ],
        [
            'method'     => 'get',
            'route'      => '/admin/tags/(:all)',
            'controller' => TagsController::class . '@dispatch',
            'model'      => TagsModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/tags/',
            'controller' => TagsController::class . '@dispatch',
            'model'      => TagsModel::class,
        ],
        [
            'method'      => 'post',
            'route'       => '/admin/tags/(:all)',
            'controller'  => TagsController::class . '@dispatch',
            'model'       => TagsModel::class,
        ],

        // Admin categories
        [
            'method'     => 'get',
            'route'      => '/admin/categories/',
            'controller' => CategoriesController::class . '@dispatch',
            'model'      => CategoriesModel::class,
        ],
        [
            'method'     => 'get',
            'route'      => '/admin/categories/(:all)',
            'controller' => CategoriesController::class . '@dispatch',
            'model'      => CategoriesModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/categories/',
            'controller' => CategoriesController::class . '@dispatch',
            'model'      => CategoriesModel::class,
        ],
        [
            'method'      => 'post',
            'route'       => '/admin/categories/(:all)',
            'controller'  => ategoriesController::class . '@dispatch',
            'model'       => CategoriesModel::class,
        ],

        // Admin comments
        [
            'method'     => 'get',
            'route'      => '/admin/comments/',
            'controller' => CommentsController::class . '@dispatch',
            'model'      => CommentsModel::class,
        ],
        [
            'method'     => 'get',
            'route'      => '/admin/comments/(:all)',
            'controller' => CommentsController::class . '@dispatch',
            'model'      => CommentsModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/comments/',
            'controller' => CommentsController::class . '@dispatch',
            'model'      => CommentsModel::class,
        ],
        [
            'method'      => 'post',
            'route'       => '/admin/comments/(:all)',
            'controller'  => CommentsController::class . '@dispatch',
            'model'       => CommentsModel::class,
        ],

        // Admin comment authors
        [
            'method'     => 'get',
            'route'      => '/admin/comment-users/',
            'controller' => CommentUsersController::class . '@dispatch',
            'model'      => CommentUsersModel::class,
        ],
        [
            'method'     => 'get',
            'route'      => '/admin/comment-users/(:all)',
            'controller' => CommentUsersController::class . '@dispatch',
            'model'      => CommentUsersModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/comment-users/',
            'controller' => CommentUsersController::class . '@dispatch',
            'model'      => CommentUsersModel::class,
        ],
        [
            'method'      => 'post',
            'route'       => '/admin/comments-users/(:all)',
            'controller'  => CommentUsersController::class . '@dispatch',
            'model'       => CommentUsersModel::class,
        ],

        // Admin settings
        [
            'method'     => 'get',
            'route'      => '/admin/settings/',
            'controller' => SettingsController::class . '@dispatch',
            'model'      => SettingsModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/settings/',
            'controller' => SettingsController::class . '@dispatch',
            'model'      => SettingsModel::class,
        ],

        // Admin account settings
        [
            'method'     => 'get',
            'route'      => '/admin/settings/account/',
            'controller' => SettingsController::class . '@dispatch',
            'model'      => SettingsModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/settings/account/',
            'controller' =>  SettingsController::class . '@dispatch',
            'model'      => SettingsModel::class,
        ],

        // Admin author settings
        [
            'method'     => 'get',
            'route'      => '/admin/settings/author/',
            'controller' => SettingsController::class . '@dispatch',
            'model'      => SettingsModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/settings/author/',
            'controller' =>  SettingsController::class . '@dispatch',
            'model'      => SettingsModel::class,
        ],

        // Admin serve settings
        [
            'method'     => 'get',
            'route'      => '/admin/settings/serve/',
            'controller' => SettingsController::class . '@dispatch',
            'model'      => SettingsModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/settings/serve/',
            'controller' =>  SettingsController::class . '@dispatch',
            'model'      => SettingsModel::class,
        ],

        // Admin access settings
        [
            'method'     => 'get',
            'route'      => '/admin/settings/access/',
            'controller' => SettingsController::class . '@dispatch',
            'model'      => SettingsModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/settings/access/',
            'controller' => SettingsController::class . '@dispatch',
            'model'      => SettingsModel::class,
        ],

        // Admin serve users
        [
            'method'     => 'get',
            'route'      => '/admin/settings/users/',
            'controller' => SettingsController::class . '@dispatch',
            'model'      => SettingsModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/settings/users/',
            'controller' => SettingsController::class . '@dispatch',
            'model'      => SettingsModel::class,
        ],

        // Admin serve tools
        [
            'method'     => 'get',
            'route'      => '/admin/settings/tools/',
            'controller' => SettingsController::class . '@dispatch',
            'model'      => SettingsModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/settings/tools/',
            'controller' => SettingsController::class . '@dispatch',
            'model'      => SettingsModel::class,
        ],

        // Admin analytics
        [
            'method'     => 'get',
            'route'      => '/admin/settings/analytics/',
            'controller' => SettingsController::class . '@dispatch',
            'model'      => SettingsModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/settings/analytics/',
            'controller' => SettingsController::class . '@dispatch',
            'model'      => SettingsModel::class,
        ],

        // Admin writer
        [
            'method'     => 'get',
            'route'      => '/admin/writer/',
            'controller' => WriterController::class . '@dispatch',
            'model'      => WriterModel::class,
        ],
        [
            'method'     => 'get',
            'route'      => '/admin/writer/(:all)',
            'controller' => WriterController::class . '@dispatch',
            'model'      => WriterModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/writer/',
            'controller' => WriterController::class . '@dispatch',
            'model'      => WriterModel::class,
        ],
        [
            'method'      => 'post',
            'route'       => '/admin/writer/(:any)',
            'controller'  => WriterController::class . '@dispatch',
            'model'       => WriterModel::class,
        ],

        // Admin media
        [
            'method'     => 'get',
            'route'      => '/admin/media/',
            'controller' => MediaLibraryController::class . '@dispatch',
            'model'      => MediaLibraryModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/media-library/',
            'controller' => MediaLibraryController::class . '@dispatch',
            'model'      => MediaLibraryModel::class,
        ],

        // Admin error logs
        [
            'method'     => 'get',
            'route'      => '/admin/logs/error-logs/',
            'controller' => ErrorLogsController::class . '@dispatch',
            'model'      => ErrorLogsModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/logs/error-logs/',
            'controller' => ErrorLogsController::class . '@dispatch',
            'model'      => ErrorLogsModel::class,
        ],

        // Admin email logs
        [
            'method'     => 'get',
            'route'      => '/admin/logs/email-logs/',
            'controller' => EmailLogsController::class . '@dispatch',
            'model'      => EmailLogsModel::class,
        ],
        [
            'method'     => 'post',
            'route'      => '/admin/logs/email-logs/',
            'controller' => EmailLogsController::class . '@dispatch',
            'model'      => EmailLogsModel::class,
        ],

        // Admin email preview
        [
            'method'     => 'get',
            'route'      => '/admin/email-preview/(:any)/',
            'controller' => EmailPreviewController::class . '@dispatch',
            'model'      => EmailPreviewModel::class,
        ],
    ];

    /**
     * {@inheritdoc}
     */
    protected function shouldRoute(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function preFilterRoutes(): void
    {
       
    }
}

