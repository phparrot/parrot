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

    public function fetchData(): \Generator
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
