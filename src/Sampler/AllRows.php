<?php

namespace PHParrot\Parrot\Sampler;

use Doctrine\DBAL\Exception\TableNotFoundException;
use PHParrot\Parrot\BaseSampler;
use PHParrot\Parrot\Sampler\Exception\TableNotFound;

class AllRows extends BaseSampler
{
    public function getName(): string
    {
        return 'All';
    }

    public function fetchData(): \Generator
    {
        $query = "SELECT * FROM " . $this->source->getConnection()->quoteIdentifier($this->tableName);

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
