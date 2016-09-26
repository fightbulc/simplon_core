<?php

namespace Core\Interfaces;

use App\AppContext;
use Core\Utils\RoutesCollection;

/**
 * Interface RegisterInterface
 * @package Core\Interfaces
 */
interface RegisterInterface
{
    /**
     * @return AppContext
     */
    public function getAppContext(): AppContext;

    /**
     * @param AppContext $appContext
     *
     * @return RegisterInterface
     */
    public function setAppContext(AppContext $appContext): RegisterInterface;

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