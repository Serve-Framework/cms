<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\routes;

use cms\router\controllers\Comments as Controller;
use cms\router\models\Comments as Model;

/**
 * CMS comments routes.
 *
 * @author Joe J. Howard
 */
class Comments extends RoutesBase
{
    /**
     * {@inheritdoc}
     */
    protected $routes =
    [
        [
            'method'     => 'post',
            'route'      => '/comments/',
            'controller' => Controller::class . '@addComment',
            'model'      => Model::class
        ],
        
    ];

    /**
     * {@inheritdoc}
     */
    protected function shouldRoute(): bool
    {
        return $this->config->get('cms.enable_comments') === true;
    }

    /**
     * {@inheritdoc}
     */
    protected function preFilterRoutes(): void
    {
       
    }
}