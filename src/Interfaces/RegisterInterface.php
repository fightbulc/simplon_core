<?php

namespace Simplon\Core\Interfaces;

use Simplon\Core\Utils\RoutesCollection;

/**
 * Interface RegisterInterface
 * @package Simplon\Core\Interfaces
 */
interface RegisterInterface
{
    /**
     * @return string
     */
    public function getWorkingDir(): string;

    /**
     * @return RoutesCollection
     */
    public function getRoutes(): RoutesCollection;

    /**
     * @return EventsInterface|null
     */
    public function getEvents();
}