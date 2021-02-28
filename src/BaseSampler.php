<?php

namespace PHParrot\Parrot;

use PHParrot\Parrot\Database\SourceDatabase;
use PHParrot\Parrot\Sampler\Sampler;

/**
 * Abstract BaseSampler class with some common functionality.
 *
 * Not for use as a type hint, use Sampler for that
 */
abstract class BaseSampler implements Sampler
{
    /**
     * Table on which the sampler is operating
     *
     * @var string
     */
    protected $tableName;

    /**
     * Connection to Source DB
     *
     * @var SourceDatabase
     */
    protected $source;

    /**
     * @var ReferenceStore
     */
    protected $referenceStore;

    /**
     * @var array
     */
    protected $referenceFields = [];

    /**
     * Max number to match (default Db order)
     *
     * @var integer
     */
    protected $limit;

    /**
     * @var \stdClass
     */
    protected $config;

    abstract protected function fetchData(): iterable;

    public function __construct(
        \stdClass $config,
        ReferenceStore $referenceStore,
        SourceDatabase $source,
        string $tableName
    ) {
        $this->config = $config;
        $this->referenceStore = $referenceStore;

        $this->referenceFields = isset($config->remember) ? $config->remember : [];
        $this->limit = isset($config->limit) ? (int)$config->limit : false;
        $this->source = $source;
        $this->tableName = $tableName;
    }

    public function getRows(): \Generator
    {
        $rows = $this->fetchData();

        foreach ($rows as $row) {
            // Store any reference fields we've been told to remember
            foreach ($this->referenceFields as $key => $variable) {
                $this->referenceStore->setReferenceByName($variable, $row[$key]);
            }

            yield $row;
        }
    }
}
