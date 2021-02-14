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

    abstract protected function fetchData(): array;

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

    public function getRows(): array
    {
        $rows = $this->fetchData();
        $references = [];

        foreach ($this->referenceFields as $key => $variable) {
            if (!array_key_exists($variable, $references)) {
                $references[$variable] = [];
            }
        }

        foreach ($rows as $row) {
            // Store any reference fields we've been told to remember
            foreach ($this->referenceFields as $key => $variable) {
                $references[$variable][] = $row[$key];
            }
        }

        foreach ($references as $reference => $values) {
            $this->referenceStore->setReferencesByName($reference, $values);
        }

        return $rows;
    }
}
