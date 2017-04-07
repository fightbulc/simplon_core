<?php

namespace Simplon\Core;

use Simplon\Core\Data\AuthRouteData;
use Simplon\Core\Interfaces\EventsInterface;
use Simplon\Core\Interfaces\RegistryInterface;
use Simplon\Core\Utils\Config;
use Simplon\Core\Utils\RoutesCollection;
use Simplon\Locale\Locale;

/**
 * @package Simplon\Core
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