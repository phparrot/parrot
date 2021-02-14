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

    public function fetchData(): array
    {
        $query = "SELECT * FROM " . $this->source->getConnection()->quote($this->tableName);

        if ($this->limit) {
            $query .= " LIMIT " . $this->limit;
        }

        try {
            $statement = $this->source->getConnection()->executeQuery($query);
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

        return $statement->fetchAllAssociative();
    }
}
