<?php

/**
 * @copyright Joe J. Howard
 * @license   https:#github.com/kanso-cms/cms/blob/master/LICENSE
 */

namespace cms\install;

use Closure;
use serve\access\Access;
use serve\config\Config;
use serve\database\Database;
use serve\http\request\Request;
use serve\http\response\exceptions\NotFoundException;
use serve\http\response\Response;
use RuntimeException;

/**
 * CMS installer.
 *
 * @author Joe J. Howard
 */
class Installer
{
    /**
     * Database Connection.
     *
     * @var \serve\database\Database
     */
    private $database;

    /**
     * Database Connection.
     *
     * @var \serve\config\Config
     */
    private $config;

    /**
     * Access manager.
     *
     * @var \cms\access\Access
     */
    private $access;

    /**
     * The path to "Install.php".
     *
     * @var string
     */
    private $installDir;

    /**
     *  Is kanso installed?
     *
     * @var bool
     */
    private $isInstalled;

    /**
     * Constructor.
     *
     * @param \serve\config\Config     $config   Config manager
     * @param \serve\database\Database $database Database manager
     * @param \cms\access\Access           $access   Access module
     */
    public function __construct(Config $config, Database $database, Access $access)
    {
        $this->config = $config;

        $this->database = $database;

        $this->access = $access;

        $this->installDir = dirname(SERVE_APPLICATION_PATH);

        if (file_exists($this->installDir . '/install.sample.php'))
        {
            throw new NotFoundException('Could not install Serve. You need to rename the "install.sample.php" to "install.php".');
        }

        $this->isInstalled();
    }

    /**
     * Returns TRUE if Serve is installed or FALSE if it is not.
     *
     * @return bool
     */
    public function isInstalled(): bool
    {
        if (!is_bool($this->isInstalled))
        {
            $this->isInstalled = !file_exists($this->installDir . '/install.php');
        }

        return $this->isInstalled;
    }

    /**
     * Install the CMS.
     *
     * @param \serve\http\request\Request   $request  Framework Request instance
     * @param \serve\http\response\Response $response Framework Response instance
     * @param \Closure                                $next     Next middleware layer
     */
    public function run(Request $request, Response $response, Closure $next): void
    {
        // Validate installation
        if ($this->isInstalled)
        {
            throw new RuntimeException('Could not install Serve. Serve is already installed. If you want to reinstall it, use the <code>reInstall()</code> method.');
        }

        // Install the Serve database
        $this->installDB();

        // Create robots.txt
        $this->installRobots();

        // Delete the install file
        unlink($this->installDir . '/install.php');

        $next();
    }

    /**
     * Show the install splash.
     *
     * @param \serve\http\request\Request   $request  Framework Request instance
     * @param \serve\http\response\Response $response Framework Response instance
     * @param \Closure                                $next     Next middleware layer
     */
    public function display(Request $request, Response $response, Closure $next): void
    {
        // Set appropriate content type header
        $response->format()->set('text/html');

        // Set the response body
        $response->body()->set($response->view()->display(dirname(__FILE__) . '/views/installed.php'));

        // Set the status
        $response->status()->set(200);

        // Disable the cache
        $response->disableCaching();

        // destroy the cookie
        $response->cookie()->destroy();

        // destroy the session
        $response->session()->destroy();

        // Start a new session
        $response->session()->start();
    }

    /**
     * Reinstall Serve to defaults but keep database and admin panel login credentials.
     */
    public function reInstall()
    {
        // Restore default configuration
        $this->config->set('cms', $this->config->getDefault('cms'));

        // Install the Serve database
        $this->installDB();

        return true;
    }

    /**
     * Install the Serve database.
     *
     * @suppress PhanUndeclaredVariable
     */
    private function installDB(): void
    {
    	// Save the database name
        $dbname = $this->config->get('database.configurations.' . $this->config->get('database.default') . '.name');

        // Create the default database
        $SQL = $this->database->create()->builder();

        // Include default Serve Settings
        include 'databaseDefaults.php';

        // Create new tables
        $SQL->CREATE_TABLE('posts', $SERVE_DEFAULTS_POSTS_TABLE);

        $SQL->CREATE_TABLE('tags', $SERVE_DEFAULTS_TAGS_TABLE);

        $SQL->CREATE_TABLE('categories', $SERVE_DEFAULTS_CATEGORIES_TABLE);

        $SQL->CREATE_TABLE('users', $SERVE_DEFAULTS_USERS_TABLE);

        $SQL->CREATE_TABLE('comments', $SERVE_DEFAULTS_COMMENTS_TABLE);

        $SQL->CREATE_TABLE('tags_to_posts', $SERVE_DEFAULTS_TAGS_TO_POSTS_TABLE);

        $SQL->CREATE_TABLE('categories_to_posts', $SERVE_DEFAULTS_CATEGORIES_TO_POSTS_TABLE);

        $SQL->CREATE_TABLE('content_to_posts', $SERVE_DEFAULTS_CONTENT_TO_POSTS_TABLE);

        $SQL->CREATE_TABLE('media_uploads', $SERVE_DEFAULTS_MEDIA_TABLE);

        $SQL->CREATE_TABLE('post_meta', $SERVE_DEFAULTS_POST_META_TABLE);

        $SQL->ALTER_TABLE('tags_to_posts')->MODIFY_COLUMN('post_id')->ADD_FOREIGN_KEY('posts', 'id');
        $SQL->ALTER_TABLE('tags_to_posts')->MODIFY_COLUMN('tag_id')->ADD_FOREIGN_KEY('tags', 'id');

        $SQL->ALTER_TABLE('categories_to_posts')->MODIFY_COLUMN('post_id')->ADD_FOREIGN_KEY('posts', 'id');
        $SQL->ALTER_TABLE('categories_to_posts')->MODIFY_COLUMN('category_id')->ADD_FOREIGN_KEY('categories', 'id');

        $SQL->ALTER_TABLE('posts')->MODIFY_COLUMN('author_id')->ADD_FOREIGN_KEY('users', 'id');

        // No foreign keys here so that you can delete
        // an attachment without having a constraint
        $SQL->ALTER_TABLE('comments')->MODIFY_COLUMN('post_id')->ADD_FOREIGN_KEY('posts', 'id');
        $SQL->ALTER_TABLE('content_to_posts')->MODIFY_COLUMN('post_id')->ADD_FOREIGN_KEY('posts', 'id');

        // Populate tables

        // Default Tags
        foreach ($SERVE_DEFAULT_TAGS as $i => $tag)
        {
            $SQL->INSERT_INTO('tags')->VALUES($tag)->EXEC();
        }

        // Default categories
        foreach ($SERVE_DEFAULT_CATEGORIES as $i => $category)
        {
            $SQL->INSERT_INTO('categories')->VALUES($category)->EXEC();
        }

        // Default media
        foreach ($SERVE_DEFAULT_IMAGES as $image)
        {
            $SQL->INSERT_INTO('media_uploads')->VALUES($image)->EXEC();
        }

        // Default user
        $SQL->INSERT_INTO('users')->VALUES($SERVE_DEFAULT_USER)->EXEC();

        // Default Articles
        foreach ($SERVE_DEFAULT_ARTICLES as $i => $article)
        {
            if (isset($article['meta']))
            {
                $meta = $article['meta'];

                unset($article['meta']);

                $SQL->INSERT_INTO('post_meta')->VALUES(['post_id' => $i+1, 'content' => serialize($meta)])->EXEC();
            }

            $SQL->INSERT_INTO('posts')->VALUES($article)->EXEC();

            foreach ($SERVE_DEFAULT_TAGS as $t => $tag)
            {
                // skip untagged
                if ($t === 0)
                {
                    continue;
                }

                $SQL->INSERT_INTO('tags_to_posts')->VALUES(['post_id' => $i+1, 'tag_id' => $t+1])->EXEC();
            }

            foreach ($SERVE_DEFAULT_CATEGORIES as $j => $tag)
            {
                // skip uncategorized
                if ($j === 0)
                {
                    continue;
                }

                $SQL->INSERT_INTO('categories_to_posts')->VALUES(['post_id' => $i+1, 'category_id' => $j+1])->EXEC();
            }

            $SQL->INSERT_INTO('content_to_posts')->VALUES(['post_id' => $i+1, 'content' => $SERVE_DEFAULT_ARTICLE_CONTENT[$i]])->EXEC();
        }

        // Extra article
        for ($x = 0; $x <= 20; $x++)
        {
            $article =
            [
                'created'     => strtotime('-18 months'),
                'modified'    => time(),
                'status'      => 'published',
                'type'        => 'post',
                'slug'        => 'html/lorum-ipsum-' . $x . '/',
                'title'       => 'Maecenas In Mauris In Turpis Blandit Dignissim',
                'excerpt'     => 'Nullam venenatis convallis neque, at molestie lorem porttitor a. Integer a viverra sapien, volutpat egestas justo',
                'author_id'   => 1,
                'thumbnail_id'   => null,
                'comments_enabled' => true,
            ];
           
            $SQL->INSERT_INTO('posts')->VALUES($article)->EXEC();

            $SQL->INSERT_INTO('categories_to_posts')->VALUES(['post_id' => $x+4, 'category_id' => 2])->EXEC();

            $SQL->INSERT_INTO('content_to_posts')->VALUES(['post_id' => $x+1, 'content' => $SERVE_DEFAULT_ARTICLE_CONTENT[3]])->EXEC();
        }

        // Default pages
        foreach ($SERVE_DEFAULT_PAGES as $i => $page)
        {
            if (isset($page['meta']))
            {
                $meta = $page['meta'];

                unset($page['meta']);

                $SQL->INSERT_INTO('post_meta')->VALUES(['post_id' => $i+1, 'content' => serialize($meta)])->EXEC();
            }

            $SQL->INSERT_INTO('posts')->VALUES($page)->EXEC();

            $SQL->INSERT_INTO('content_to_posts')->VALUES(['post_id' => $i+1, 'content' => $SERVE_DEFAULT_PAGE_CONTENT[$i]])->EXEC();
        }
    }

    /**
     * Create the robots.txt file.
     */
    private function installRobots(): void
    {
        $enabled = $this->config->get('cms.security.enable_robots');
        $content = $this->config->get('cms.security.robots_text_content');

        if (!$enabled)
        {
            $this->access->saveRobots($this->access->blockAllRobotsText());
        }
        elseif ($enabled && empty($content))
        {
            $this->access->saveRobots($this->access->defaultRobotsText());
        }
        else
        {
            $this->access->saveRobots($content);
        }
    }
}
