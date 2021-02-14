<?php

namespace PHParrot\Parrot\Sampler;

use Doctrine\DBAL\Exception\TableNotFoundException;
use PHParrot\Parrot\BaseSampler;
use PHParrot\Parrot\Sampler\Exception\RequiredConfigurationValueNotProvided;
use PHParrot\Parrot\Sampler\Exception\TableNotFound;

class NewestById extends BaseSampler
{
    public function getName(): string
    {
        return 'NewestById';
    }

    public function fetchData(): array
    {
        if (!isset($this->config->idField)) {
            throw new RequiredConfigurationValueNotProvided('The required parameter \'idField\' was not provided');
        }

        if (!isset($this->config->quantity)) {
            throw new RequiredConfigurationValueNotProvided('The required parameter \'quantity\' was not provided');
        }

        try {
            $query = sprintf(
                'SELECT * FROM %s ORDER BY %s DESC LIMIT %s',
                $this->source->getConnection()->quote($this->tableName),
                $this->config->idField,
                $this->config->quantity
            );

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
