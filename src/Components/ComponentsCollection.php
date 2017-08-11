<?php

namespace Simplon\Core\Components;

use Simplon\Core\Interfaces\RegistryInterface;

class ComponentsCollection
{
    /**
     * @var RegistryInterface[]
     */
    private $components = [];

    /**
     * @return RegistryInterface[]
     */
    public function get(): array
    {
        return $this->components;
    }

    /**
     * @param RegistryInterface $component
     *
     * @return ComponentsCollection
     */
    public function add(RegistryInterface $component): ComponentsCollection
    {
        $this->components[get_class($component)] = $component;

        return $this;
    }
}