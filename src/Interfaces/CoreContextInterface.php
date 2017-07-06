<?php

namespace Simplon\Core\Interfaces;

use Simplon\Core\Storage\SessionStorage;
use Simplon\Core\Utils\Config;
use Simplon\Core\Utils\EventsHandler;

/**
 * Interface CoreContextInterface
 * @package Simplon\Core\Interfaces
 */
interface CoreContextInterface
{
    /**
     * @return array
     */
    public function getRemoteEnvs(): array;

    /**
     * @param null|string $workingDir
     *
     * @return Config
     */
    public function getConfig(?string $workingDir = null): Config;

    /**
     * @return EventsHandler
     */
    public function getEventsHandler(): EventsHandler;

    /**
     * @return SessionStorage
     */
    public function getSessionStorage(): SessionStorage;
}