<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\admin\controllers\dash;

use Closure;
use serve\http\request\Request;
use serve\http\response\Response;
use serve\mvc\model\Model;
use serve\utility\Pluralize;
use cms\admin\controllers\BaseController;

/**
 * Admin panel custom posts controller.
 *
 * @author Joe J. Howard
 */
class Posts extends BaseController
{
    /**
     * {@inheritdoc}
     */
    protected $template = 'dash/posts.php';

    /**
     * {@inheritdoc}
     */
    protected $pageTitle = 'Posts';

    /**
     * Constructor.
     *
     * @param \serve\http\request\Request   $request    Request instance
     * @param \serve\http\response\Response $response   Response instance
     * @param Closure                       $next       Next middleware closure
     * @param string                        $modelClass Full namespaced class name of the model
     * @param string                        $postType   Post type
     */
    public function __construct(Request $request, Response $response, Closure $next, string $modelClass, string $postType)
    {
        $this->nextMiddleware = $next;

        $this->pageTitle = ucfirst(Pluralize::convert($postType));

        $this->loadModel($modelClass);

        $this->model->setPostType($postType);
    }
}