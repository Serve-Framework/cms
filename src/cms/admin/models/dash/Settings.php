<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\models\dash;

use cms\admin\models\BaseModel;
use serve\http\response\exceptions\InvalidTokenException;
use serve\http\response\exceptions\RequestException;
use serve\utility\Str;

/**
 * Settings model.
 *
 * @author Joe J. Howard
 */
class Settings extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public function onGET(): array|false
    {
        if ($this->isLoggedIn())
        {
            return $this->parseGet();
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function onPOST(): array|false
    {
        if ($this->isLoggedIn())
        {
            if (!isset($this->post['access_token']) || !$this->Gatekeeper->verifyToken($this->post['access_token']))
            {
                throw new InvalidTokenException('Bad Admin Panel POST Request. The CSRF token was either not provided or was invalid.');
            }

            return $this->parsePost();
        }

        throw new RequestException(500, 'Bad Admin Panel POST Request. The user was not logged in as an admin.');
    }

    /**
     * {@inheritdoc}
     */
    public function onAJAX(): array|false|string
    {
        return false;
    }

    /**
     * Parse the $_GET request variables and filter the articles for the requested page.
     *
     * @return array
     */
    private function parseGet(): array
    {
        $_themes = array_filter(glob($this->Config->get('cms.themes_path') . '/*'), 'is_dir');

        $themes  = [];

        foreach ($_themes as $i => $_theme)
        {
            $themes[] = substr($_theme, strrpos($_theme, '/') + 1);
        }

        return 
        [
            'themes' => $themes,
        ];
    }

    /**
     * Parse and validate the POST request from any submitted forms.
     *
     * @return array|false
     */
    private function parsePost()
    {
        if (isset($this->post['form_name']))
        {
            $formName = $this->post['form_name'];

            if ($formName === 'account_settings')
            {
                return $this->submitAccountSettings();
            }
            elseif ($formName === 'author_settings')
            {
                return $this->submitAuthorSettings();
            }
            elseif ($formName === 'serve_settings')
            {
                return $this->submitServeSettings();
            }
            elseif ($formName === 'access_settings')
            {
                return $this->submitAccessSettings();
            }
            elseif ($formName === 'analytics_settings')
            {
                return $this->submitAnalyticsSettings();
            }
            elseif ($formName === 'restore_serve')
            {
                return $this->submitRestoreServe();
            }
            elseif ($formName === 'invite_user')
            {
                return $this->submitInviteUser();
            }
            elseif ($formName === 'change_user_role')
            {
                return $this->submitChangeUserRole();
            }
            elseif ($formName === 'delete_user')
            {
                return $this->submitDeleteUser();
            }
        }

        return false;
    }

    /**
     * Parse, validate and process the account settings form.
     *
     * @return array|false
     */
    private function submitAccountSettings()
    {
        $rules =
        [
            'username' => ['required', 'alpha_dash', 'max_length(100)', 'min_length(4)'],
            'email'    => ['required', 'email'],
            'password' => ['max_length(100)', 'min_length(6)'],
        ];
        $filters =
        [
            'username' => ['trim', 'string'],
            'email'    => ['trim', 'string', 'email'],
            'password' => ['trim'],
            'email_notifications' => ['trim', 'boolean'],
        ];

        $validator = $this->container->Validator->create($this->post, $rules, $filters);

        if (!$validator->isValid())
        {
            $errors = $validator->getErrors();

            return $this->postMessage('warning', array_shift($errors));
        }

        $post = $validator->filter();

        $username = $post['username'];
        $email    = $post['email'];
        $password = $post['password'];
        $emailNotifications = $post['email_notifications'];

        // Grab the user's object
        $user = $this->Gatekeeper->getUser();

        // Validate that the username/ email doesn't exist already
        // only if the user has changed either value
        if ($email !== $user->email)
        {
            if ($this->UserManager->provider()->byEmail($email))
            {
                return $this->postMessage('warning', 'Another user already exists with that email. Please try another email address.');
            }
        }
        if ($username !== $user->username)
        {
            if ($this->UserManager->provider()->byUsername($username))
            {
                return $this->postMessage('warning', 'Another user already exists with that username. Please try another username.');
            }
        }

        // Update the user
        $user->username = $username;
        $user->email    = $email;
        $user->email_notifications = $emailNotifications;

        // If they changed their password lets update it
        if ($password !== '' && !empty($password))
        {
            $user->hashed_pass = utf8_encode($this->Crypto->password()->hash($password));
        }

        $user->save();

        $this->Gatekeeper->refreshUser();

        return $this->postMessage('success', 'Your account settings were successfully updated!');
    }

    /**
     * Parse, validate and process the author settings form.
     *
     * @return array|false
     */
    private function submitAuthorSettings()
    {
        $rules =
        [
            'name'        => ['required', 'alpha_space', 'max_length(50)', 'min_length(3)'],
            'slug'        => ['required', 'alpha_dash', 'max_length(50)', 'min_length(3)'],
            'description' => ['required'],
            'facebook'    => ['url'],
            'twitter'     => ['url'],
            'gplus'       => ['url'],
            'instagram'   => ['url'],
        ];
        $filters =
        [
            'name'         => ['trim', 'string'],
            'slug'         => ['trim', 'string'],
            'description'  => ['trim', 'string'],
            'facebook'     => ['trim'],
            'twitter'      => ['trim'],
            'gplus'        => ['trim'],
            'instagram'    => ['trim'],
            'thumbnail_id' => ['trim', 'integer'],
        ];

        $validator = $this->container->Validator->create($this->post, $rules, $filters);

        if (!$validator->isValid())
        {
            $errors = $validator->getErrors();

            return $this->postMessage('warning', array_shift($errors));
        }

        // Sanitize and validate the POST
        $post = $validator->filter();

        // Grab the Row and update settings
        $user = $this->Gatekeeper->getUser();

        // Change authors details
        $user->name         = $post['name'];
        $user->slug         = $post['slug'];
        $user->facebook     = $post['facebook'];
        $user->twitter      = $post['twitter'];
        $user->gplus        = $post['gplus'];
        $user->instagram    = $post['instagram'];
        $user->description  = $post['description'];
        $user->thumbnail_id = $post['thumbnail_id'];
        $user->save();

        $this->Gatekeeper->refreshUser();

        return $this->postMessage('success', 'Your author information was successfully updated!');
    }

    /**
     * Parse and validate the Serve settings from the POST request.
     *
     * @return array|false
     */
    private function submitServeSettings()
    {
        // Validate the user is an admin
        if ($this->Gatekeeper->getUser()->role !== 'administrator')
        {
            return false;
        }

        $post  = $this->post;
        $rules =
        [
            'posts_per_page'     => ['required'],
            'thumbnail_quality'  => ['required', 'greater_than_or_equal_to(0)', 'less_than_or_equal_to(9)'],
            'cdn_url'            => ['url'],
            'site_title'         => ['required'],
            'site_description'   => ['required'],
            'sitemap_url'        => ['required'],
            'theme'              => ['required'],
            'permalinks'         => ['required'],
        ];
        $filters =
        [
            'enable_authors'     => ['boolean'],
            'enable_cats'        => ['boolean'],
            'enable_tags'        => ['boolean'],
            'enable_cdn'         => ['boolean'],
            'enable_cache'       => ['boolean'],
            'enable_comments'    => ['boolean'],
            'enable_attachments' => ['boolean'],
            'posts_per_page'     => ['integer'],
            'thumbnail_quality'  => ['integer'],
            'cdn_url'            => ['trim'],
            'site_title'         => ['trim'],
            'site_description'   => ['trim'],
            'sitemap_url'        => ['trim'],
            'theme'              => ['trim'],
            'permalinks'         => ['trim'],
            'blog_location'      => ['trim'],
        ];

        $validator = $this->container->Validator->create($this->post, $rules, $filters);

        if (!$validator->isValid())
        {
            $errors = $validator->getErrors();

            return $this->postMessage('warning', array_shift($errors));
        }

        $post = $validator->filter();

        // Validate the permalinks
        if (!$this->validatePermalinks($post['permalinks']))
        {
            return $this->postMessage('warning', 'The permalinks value you entered is invalid. Please ensure you enter a valid permalink structure - e.g. "year/month/postname/".');
        }

        // Validate the CDN URL
        if ($post['enable_cdn'] && !filter_var($post['cdn_url'], FILTER_VALIDATE_URL))
        {
            return $this->postMessage('warning', 'The CDN URL you entered is invalid. Please provide a valid URL.');
        }

        // Filter the permalinks
        $permalinks = $this->filterPermalinks($post['permalinks']);

        // Previous permalinks value
        $oldPermalinks = $this->Config->get('cms.permalinks');

        // Sanitize the blog location
        $post['blog_location'] = !empty($post['blog_location']) ? rtrim(ltrim($post['blog_location'], '/'), '/') : false;

        $cms =
        [
            'theme_name'        => $post['theme'],
            'site_title'        => $post['site_title'],
            'site_description'  => $post['site_description'],
            'blog_location'     => $post['blog_location'],
            'sitemap_route'     => $post['sitemap_url'],
            'permalinks'        => $permalinks['permalinks'],
            'permalinks_route'  => $permalinks['permalinks_route'],
            'posts_per_page'    => $post['posts_per_page'] < 1 ? 10 : intval($post['posts_per_page']),
            'route_tags'        => $post['enable_tags'],
            'route_categories'  => $post['enable_cats'],
            'route_attachments' => $post['enable_attachments'],
            'route_authors'     => $post['enable_authors'],
            'enable_comments'   => $post['enable_comments'],
        ];

        foreach ($cms as $key => $val)
        {
            $this->Config->set('cms.' . $key, $val);
        }

        $this->Config->set('pixl.compression', $post['thumbnail_quality']);

        $this->Config->set('cdn.enabled', $post['enable_cdn']);
        $this->Config->set('cdn.host', $post['cdn_url']);

        $this->Config->set('cache.http_cache_enabled', $post['enable_cache']);

        $this->Config->save();

        // If permalinks were changed - reset all post slugs
        if ($oldPermalinks !== $permalinks['permalinks'])
        {
            $this->resetPostSlugs();
        }

        return $this->postMessage('success', 'Serve settings successfully updated!');

    }

    /**
     * Parse and validate the access settings.
     *
     * @return array|false
     */
    private function submitAccessSettings()
    {
        $enableIpBlock = !isset($this->post['enable_ip_block']) ? false : Str::bool($this->post['enable_ip_block']);
        $blockRobots   = isset($this->post['block_robots']) ? true : false;

        $robotsContent = !isset($this->post['robots_content']) ? '' : trim($this->post['robots_content']);
        $ipWhitelist   = !isset($this->post['ip_whitelist']) ? [] : array_filter(array_map('trim', explode(',', $this->post['ip_whitelist'])));

        // Save robots
        if ($blockRobots)
        {
            $this->Access->saveRobots($this->Access->blockAllRobotsText());
            $robotsContent = $this->Access->blockAllRobotsText();
        }
        elseif (empty($robotsContent))
        {
            $this->Access->saveRobots($this->Access->defaultRobotsText());
            $robotsContent = $this->Access->defaultRobotsText();
        }
        else
        {
            $this->Access->saveRobots($robotsContent);
        }

        // Enable ip blocking
        $this->Config->set('cms.security.enable_robots', !$blockRobots);
        $this->Config->set('cms.security.ip_blocked', $enableIpBlock);
        $this->Config->set('cms.security.ip_whitelist', $ipWhitelist);
        $this->Config->set('cms.security.robots_text_content', $robotsContent);
        $this->Config->save();

        return $this->postMessage('success', 'Security settings successfully updated!');
    }

    /**
     * Parse and validate the Serve settings from the POST request.
     *
     * @return array|false
     */
    private function submitAnalyticsSettings()
    {
        // Validate the user is an admin
        if ($this->Gatekeeper->getUser()->role !== 'administrator')
        {
            return false;
        }

        $post  = $this->post;
        $rules = [];
        $filters =
        [
            'gAnalytics_enable'  => ['boolean'],
            'gAdwords_enable'    => ['boolean'],
            'fbPixel_enable'     => ['boolean'],
            'gAnalytics_id'      => ['trim', 'string'],
            'gAdwords_id'        => ['trim', 'string'],
            'gAdwords_cnv_id'    => ['trim', 'string'],
            'fbPixel_id'         => ['trim', 'string'],
        ];

        $validator = $this->container->Validator->create($this->post, $rules, $filters);

        if (!$validator->isValid())
        {
            $errors = $validator->getErrors();

            return $this->postMessage('warning', array_shift($errors));
        }

        $post = $validator->filter();

        // Save settings
        $this->Config->set('analytics.google.analytics.enabled', $post['gAnalytics_enable']);
        $this->Config->set('analytics.google.adwords.enabled', $post['gAdwords_enable']);
        $this->Config->set('analytics.facebook.enabled', $post['fbPixel_enable']);

        $this->Config->set('analytics.google.analytics.id', $post['gAnalytics_id']);
        $this->Config->set('analytics.google.adwords.id', $post['gAdwords_id']);
        $this->Config->set('analytics.google.adwords.conversion', $post['gAdwords_cnv_id']);
        $this->Config->set('analytics.facebook.pixel', $post['fbPixel_id']);

        $this->Config->save();

        return $this->postMessage('success', 'Analytics settings successfully updated!');

    }

    /**
     * Validate a permalink value.
     *
     * @param  string $url The url to be converted
     * @return bool
     */
    private function validatePermalinks(string $url): bool
    {
        $permaLink = '';
        $route     = '';
        $urlPieces = explode('/', $url);
        $map =
        [
            'year'     => '(:year)',
            'month'    => '(:month)',
            'day'      => '(:day)',
            'hour'     => '(:hour)',
            'minute'   => '(:minute)',
            'second'   => '(:second)',
            'postname' => '(:postname)',
            'category' => '(:category)',
            'author'   => '(:author)',
        ];

        foreach ($urlPieces as $key)
        {
            if (isset($map[$key]))
            {
                $permaLink .= $key . DIRECTORY_SEPARATOR;
                $route     .= $map[$key] . DIRECTORY_SEPARATOR;
            }
        }

        if ($permaLink === '' || $route === '' || strpos($permaLink, 'postname') === false)
        {
            return false;
        }

        return true;
    }

    /**
     * Filter the permalinks.
     *
     * @param  string $url The url to be converted
     * @return array  Array with the the actual link and the route
     */
    private function filterPermalinks($url)
    {
        $permaLink = '';
        $route     = '';
        $urlPieces = explode('/', $url);
        $map = [
            'year'     => '(:year)',
            'month'    => '(:month)',
            'day'      => '(:day)',
            'hour'     => '(:hour)',
            'minute'   => '(:minute)',
            'second'   => '(:second)',
            'postname' => '(:postname)',
            'category' => '(:category)',
            'author'   => '(:author)',
        ];

        foreach ($urlPieces as $key)
        {
            if (isset($map[$key]))
            {
                $permaLink .= $key . DIRECTORY_SEPARATOR;
                $route     .= $map[$key] . DIRECTORY_SEPARATOR;
            }
            else
            {
                $permaLink .= Str::slug($key) . DIRECTORY_SEPARATOR;
                $route     .= Str::slug($key) . DIRECTORY_SEPARATOR;
            }
        }

        $permaLink = trim($permaLink, '/') . '/';
        $route     = trim($route, '/') . '/';

        return [
            'permalinks' => $permaLink,
            'permalinks_route' => $route,
        ];
    }

    /**
     * Parse, validate and process the add new user form.
     *
     * @return array|false
     */
    private function submitInviteUser()
    {
        // Validate the user is an admin
        if (!$this->Gatekeeper->getUser()->role === 'administrator')
        {
            return false;
        }

        $rules =
        [
            'email' => ['required', 'email'],
            'role'  => ['required', 'in(["administrator", "writer"])'],
        ];
        $filters =
        [
            'username' => ['trim', 'email'],
        ];

        $validator = $this->container->Validator->create($this->post, $rules, $filters);

        if (!$validator->isValid())
        {
            $errors = $validator->getErrors();

            return $this->postMessage('warning', array_shift($errors));
        }

        $post = $validator->filter();

        if ($this->Gatekeeper->getUser()->email === $post['email'])
        {
            return $this->postMessage('warning', 'Another user is already registered with that email address.');
        }

        $user = $this->UserManager->provider()->byEmail($post['email']);

        if ($user && $user->status === 'confirmed')
        {
            return $this->postMessage('warning', 'Another user is already registered with that email address.');
        }

        // If theyre deleted or pending re-invite them
        if (!$user || ($user && $user->status !== 'confirmed'))
        {
            if ($this->UserManager->createAdmin($post['email'], $post['role']))
            {
                return $this->postMessage('success', 'The user was successfully sent a registration invite.');
            }

        }

        return false;
    }

    /**
     * Parse, validate and process the delete user form.
     *
     * @return array|false
     */
    private function submitDeleteUser()
    {
        // Validate the user is an admin
        if (!$this->Gatekeeper->getUser()->role === 'administrator')
        {
            return false;
        }

        $rules =
        [
            'user_id' => ['required', 'integer'],
        ];
        $filters =
        [
            'user_id' => ['trim', 'integer'],
        ];

        $validator = $this->container->Validator->create($this->post, $rules, $filters);

        if (!$validator->isValid())
        {
            $errors = $validator->getErrors();

            return $this->postMessage('warning', array_shift($errors));
        }

        $post = $validator->filter();

        if ($post['user_id'] === $this->Gatekeeper->getUser()->id || $post['user_id'] === 1)
        {
            return false;
        }

        $user = $this->UserManager->provider()->byId($post['user_id']);

        if ($user)
        {
            $user->delete();

            return $this->postMessage('success', 'The user was successfully deleted.');
        }

        return false;
    }

    /**
     * Parse, validate and process the change user role form.
     *
     * @return array|false
     */
    private function submitChangeUserRole()
    {
        // Validate the user is an admin
        if (!$this->Gatekeeper->getUser()->role === 'administrator')
        {
            return false;
        }

        $rules =
        [
            'user_id' => ['required', 'integer'],
            'role'    => ['required', 'in(["administrator", "writer"])'],
        ];
        $filters =
        [
            'user_id' => ['trim', 'integer'],
        ];

        $validator = $this->container->Validator->create($this->post, $rules, $filters);

        if (!$validator->isValid())
        {
            $errors = $validator->getErrors();

            return $this->postMessage('warning', array_shift($errors));
        }

        $post = $validator->filter();

        if ($post['user_id'] === $this->Gatekeeper->getUser()->id || $post['user_id'] === 1)
        {
            return false;
        }

        $user = $this->UserManager->provider()->byId($post['user_id']);

        if ($user)
        {
            $user->role = $post['role'];

            $user->save();

            return $this->postMessage('success', 'The user was successfully deleted.');
        }

        return false;
    }

    /**
     * Update and reset post slugs when permalinks have changed.
     */
    private function resetPostSlugs(): void
    {
        // Select the posts
        $posts = $this->sql()->SELECT('posts.id')->FROM('posts')->FIND_ALL();

        if ($posts)
        {
            foreach ($posts as $row)
            {
                $post = $this->PostManager->byId($row['id']);

                $post->save();
            }
        }
    }

    /**
     * Parse, validate and process the restore Serve form.
     *
     * @return array|null
     */
    private function submitRestoreServe()
    {
        // Validate the user is an admin
        if ($this->Gatekeeper->getUser()->role === 'administrator')
        {
            if ($this->Installer->reInstall())
            {
                $this->Response->session()->destroy();

                $this->Response->session()->start();

                $this->Response->cookie()->destroy();

                $this->Response->redirect($this->Request->environment()->HTTP_HOST . '/admin/login/');

                return null;
            }
        }

        return $this->postMessage('danger', 'There was an error processing your request.');
    }
}
