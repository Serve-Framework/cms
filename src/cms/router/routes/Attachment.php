<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\routes;

use cms\router\controllers\Content as Controller;
use cms\router\models\Attachment as Model;

/**
 * CMS attachement routes.
 *
 * @author Joe J. Howard
 */
class Attachment extends RoutesBase
{
    /**
     * {@inheritdoc}
     */
    protected $routes =
    [
        [
            'method'     => 'get',
            'route'      => '/attachment/(:any)/',
            'controller' => Controller::class . '@apply',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => '/attachment/(:any)/feed/',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => '/attachment/(:any)/feed/rss/',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => '/attachment/(:any)/feed/atom/',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
        [
            'method'     => 'get',
            'route'      => '/attachment/(:any)/feed/rdf.',
            'controller' => Controller::class . '@rss',
            'model'      => Model::class
        ],
    ];

    /**
     * {@inheritdoc}
     */
    protected function shouldRoute(): bool
    {
        return $this->config->get('cms.route_attachments') === true;
    }

    /**
     * {@inheritdoc}
     */
    protected function preFilterRoutes(): void
    {
       
    }
}