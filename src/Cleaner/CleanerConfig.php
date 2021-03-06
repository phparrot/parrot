<?php

namespace PHParrot\Parrot\Cleaner;

class CleanerConfig
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var array
     */
    private $parameters = [];

    private function __construct(string $name, array $parameters)
    {
        $this->name = $name;
        $this->parameters = $parameters;
    }

    public static function fromString(string $config)
    {
        $parameters = explode(':', $config);
        $name = \strtolower(\array_shift($parameters));

        return new self($name, $parameters);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
