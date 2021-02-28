<?php

namespace PHParrot\Parrot;

class ReferenceStore
{
    protected $references = [];

    public function setReferencesByName(string $name, array $references): void
    {
        $this->references[$name] = $references;
    }

    public function setReferenceByName(string $name, string $reference): void
    {
        if (false === isset($this->references[$name])) {
            $this->references[$name] = [];
        }

        $this->references[$name][] = $reference;
    }

    public function getReferencesByName(string $name): array
    {
        return isset($this->references[$name]) ? $this->references[$name] : [];
    }
}
