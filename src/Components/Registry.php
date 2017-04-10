<?php

namespace Simplon\Core\Components;

use Simplon\Core\Data\AuthRouteData;
use Simplon\Core\Interfaces\EventsInterface;
use Simplon\Core\Interfaces\RegistryInterface;
use Simplon\Core\Utils\RoutesCollection;

/**
 * @package Simplon\Core\Components
 */
abstract class Registry implements RegistryInterface
{
    abstract public function getContext();

    /**
     * @return null|RoutesCollection
     */
    public function getRoutes(): ?RoutesCollection
    {
        return null;
    }

    /**
     * @return null|AuthRouteData[]
     */
    public function getAuthRoutes(): ?array
    {
        return null;
    }

    /**
     * @return null|EventsInterface
     */
    public function getEvents(): ?EventsInterface
    {
        return null;
    }
}