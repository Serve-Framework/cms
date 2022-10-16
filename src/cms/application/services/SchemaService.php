<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\application\services;

use cms\schema\Schema;
use serve\application\services\Service;

/**
 * Schema.org service.
 *
 * @author Joe J. Howard
 */
class SchemaService extends Service
{
    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->container->setInstance('Schema', new Schema);
    }
}
