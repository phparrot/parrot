<?php

namespace PHParrot\Parrot\Sampler;

use PHParrot\Parrot\BaseSampler;

class NewestById extends BaseSampler implements Sampler
{

    /**
     * @var string
     */
    protected $idField;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * Return a unique name for this sampler for informational purposes
     *
     * @return string
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'NewestById';
    }


    /**
     * Return all rows that this sampler would copy
     *
     * @inheritdoc
     */
    public function getRows(): array
    {
        $this->quantity = (int)$this->demandParameterValue($this->config, 'quantity'); // TODO possibly rename to 'limit'
        $this->idField = $this->demandParameterValue($this->config, 'idField');

        $query = $this->source->getConnection()->createQueryBuilder()->select('*')->from($this->tableName)
            ->addOrderBy($this->idField, 'DESC')
            ->setMaxResults($this->quantity)
            ->execute();

        return $query->fetchAll();
    }
}
