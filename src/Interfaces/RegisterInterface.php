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
     * @return AppContextInterface
     */
    public function getAppContext(): AppContextInterface;

    /**
     * @param AppContextInterface $appContext
     *
     * @return RegisterInterface
     */
    public function setAppContext(AppContextInterface $appContext): RegisterInterface;

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