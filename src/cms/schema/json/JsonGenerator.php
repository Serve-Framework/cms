<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\schema\json;

use cms\wrappers\managers\CategoryManager;
use cms\wrappers\managers\CommentManager;
use cms\wrappers\managers\MediaManager;
use cms\wrappers\managers\PostManager;
use cms\wrappers\managers\TagManager;
use serve\database\wrappers\managers\UserManager;
use serve\config\Config;
use serve\http\request\Request;
use serve\http\response\Response;

/**
 * JSON generator base class.
 *
 * @author Joe J. Howard
 */
abstract class JsonGenerator
{
    /**
     * Request instance.
     *
     * @var \serve\http\request\Request
     */
    protected $Request;

    /**
     * Response instance.
     *
     * @var \serve\http\response\Response
     */
    protected $Response;

    /**
     * Config instance.
     *
     * @var \serve\config\Config
     */
    protected $Config;

    /**
     * PostManager instance.
     *
     * @var \cms\wrappers\managers\PostManager
     */
    protected $PostManager;

    /**
     * CategoryManager instance.
     *
     * @var \cms\wrappers\managers\CategoryManager
     */
    protected $CategoryManager;

    /**
     * TagManager instance.
     *
     * @var \cms\wrappers\managers\TagManager
     */
    protected $TagManager;

    /**
     * UserManager instance.
     *
     * @var \serve\database\wrappers\managers\UserManager
     */
    protected $UserManager;

    /**
     * MediaManager instance.
     *
     * @var \cms\wrappers\managers\MediaManager
     */
    protected $MediaManager;

    /**
     * CommentManager instance.
     *
     * @var \cms\wrappers\managers\CommentManager
     */
    protected $CommentManager;

    /**
     * Constructor.
     *
     * @param \serve\http\request\Request        $request         Request instance
     * @param \serve\http\response\Response      $response        Response instance
     * @param \serve\config\Config               $config          Config instance
     * @param \cms\wrappers\managers\PostManager     $postmanager     PostManager instance
     * @param \cms\wrappers\managers\CategoryManager $categorymanager CategoryManager instance
     * @param \cms\wrappers\managers\TagManager      $tagmanager      TagManager instance
     * @param \serve\database\wrappers\managers\UserManager     $usermanager     UserManager instance
     * @param \cms\wrappers\managers\MediaManager    $mediamanager    MediaManager instance
     * @param \cms\wrappers\managers\CommentManager  $commentmanager  commentmanager instance
     */
    public function __construct(Request $request, Response $response, Config $config, PostManager $postmanager,CategoryManager $categorymanager, TagManager $tagmanager, UserManager $usermanager, MediaManager $mediamanager, CommentManager $commentmanager)
    {
        $this->Request         = $request;
        $this->Response        = $response;
        $this->Config          = $config;
        $this->PostManager     = $postmanager;
        $this->CategoryManager = $categorymanager;
        $this->TagManager      = $tagmanager;
        $this->UserManager     = $usermanager;
        $this->MediaManager    = $mediamanager;
        $this->CommentManager  = $commentmanager;
    }
}
