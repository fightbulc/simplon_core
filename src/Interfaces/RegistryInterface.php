<?php

namespace Simplon\Core\Interfaces;

use Simplon\Core\Data\AuthRouteData;
use Simplon\Core\Utils\RoutesCollection;

/**
 * @package Simplon\Core\Interfaces
 */
interface RegistryInterface
{
    public function getContext();

    /**
     * @return null|RoutesCollection
     */
    public function getRoutes(): ?RoutesCollection;

    /**
     * @return null|AuthRouteData[]
     */
    public function getAuthRoutes(): ?array;

    /**
     * @return null|EventsInterface
     */
    public function getEvents(): ?EventsInterface;
}