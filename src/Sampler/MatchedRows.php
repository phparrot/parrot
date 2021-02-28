<?php

namespace PHParrot\Parrot\Sampler;

use Doctrine\DBAL\Exception\TableNotFoundException;
use PHParrot\Parrot\BaseSampler;
use PHParrot\Parrot\Sampler\Exception\RequiredConfigurationValueNotProvided;
use PHParrot\Parrot\Sampler\Exception\TableNotFound;

/**
 * Sample DB rows that match specific values
 *
 * Can create IN constraints by setting an array as the RHS of the constraint, otherwise set a scalar
 * Can specify a list of WHERE clauses
 *
 * eg:
 * "api_clients": {
 *     "sampler": "matched",
 *     "constraints": {
 *         "cobrand_prefix": [
 *             "candis",
 *             "www"
 *         ]
 *     },
 *     "where": [
 *         "created > NOW()"
 *     ]
 * }
 */
class MatchedRows extends BaseSampler
{
    public function getName(): string
    {
        return 'Matched';
    }

    public function fetchData(): \Generator
    {
        if (!isset($this->config->constraints) && !isset($this->config->where)) {
            throw new RequiredConfigurationValueNotProvided('Either parameter \'constraints\' or \'where\' is required but neither was provided');
        }

        $where = $this->config->where ?? [];
        $constraints = $this->config->constraints ?? [];

        $query = sprintf("SELECT * FROM %s WHERE 1", $this->source->getConnection()->quote($this->tableName));

        foreach ($constraints as $field => $value) {
            // Handle remembered reference variables
            if (is_string($value) && strpos($value, '$') === 0) {
                $variable = substr($value, 1);
                $value = $this->referenceStore->getReferencesByName($variable);
                if (is_null($value)) {
                    throw new \RuntimeException("'\${$variable}' is not a recognised remembered value");
                }
            }

            if (is_array($value)) {
                if (count($value)) {
                    $query .= ' AND ' . $this->source->getConnection()->quoteIdentifier($field) . ' IN (' . implode(', ', \array_map(function ($item) {
                            return $this->source->getConnection()->quote($item);
                        }, $value)) . ')';
                }
            } else {
                $query .= " AND " . $this->source->getConnection()->quoteIdentifier($field) . ' = ' . $this->source->getConnection()->quote($value);
            }
        }

        foreach ($where as $condition) {
            $query .= " AND " . $condition;
        }

        if ($this->limit) {
            $query .= " LIMIT " . $this->limit;
        }

        try {
            foreach ($this->source->getConnection()->iterateAssociative($query) as $row) {
                yield $row;
            }
        } catch (TableNotFoundException $exception) {
            throw new TableNotFound(
                sprintf(
                    "Table %s does not exist",
                    $this->tableName
                ),
                0,
                $exception
            );
        }
    }
}
