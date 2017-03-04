<?php

namespace Simplon\Core\Interfaces;

use Simplon\Core\Data\AuthRouteData;
use Simplon\Core\Utils\RoutesCollection;

/**
 * Interface RegisterInterface
 * @package Simplon\Core\Interfaces
 */
interface RegisterInterface
{
    public function getContext();

    /**
     * @return string
     */
    public function getWorkingDir(): string;

    /**
     * @return RoutesCollection
     */
    public function getRoutes(): RoutesCollection;

    /**
     * @return AuthRouteData[]|null
     */
    public function getAuth(): ?array;

    /**
     * @return null|EventsInterface
     */
    public function getEvents(): ?EventsInterface;
}