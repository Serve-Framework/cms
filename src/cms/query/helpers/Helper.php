<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\query\helpers;

use cms\query\Query;
use serve\common\SqlBuilderTrait;
use serve\ioc\Container;

/**
 * CMS Query object.
 *
 * @author Joe J. Howard
 */
abstract class Helper
{
    use SqlBuilderTrait;

    /**
     * IoC container instance.
     *
     * @var \serve\ioc\Container
     */
    protected $container;

    /**
     * Query instance.
     *
     * @var \cms\query\Query
     */
    protected $query;

    /**
     * Constructor.
     *
     * @param \serve\ioc\Container $container IoC container
     * @param \cms\query\Query         $query     CMS query instance
     */
    public function __construct(Container $container, Query $query)
    {
        $this->container = $container;

        $this->query = $query;
    }
}
