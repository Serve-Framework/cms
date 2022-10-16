<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\wrappers\managers;

use serve\database\builder\Builder;

/**
 * Provider manager base class.
 *
 * @author Joe J. Howard
 */
abstract class Manager
{
    /**
     * SQL query builder.
     *
     * @var \serve\database\builder\Builder
     */
    protected $SQL;

    /**
     * Provider.
     *
     * @var mixed
     */
    protected $provider;

    /**
     * Default constructor.
     *
     * @param \serve\database\builder\Builder $SQL      SQL query builder
     * @param mixed                                   $provider Provider manager
     */
    public function __construct(Builder $SQL, $provider)
    {
        $this->SQL = $SQL;

        $this->provider = $provider;
    }

    /**
     * Get the provider.
     *
     * @return mixed
     */
    abstract public function provider();
}
