<?php

namespace Simplon\Core\Interfaces;

use Simplon\Core\Storage\SessionStorage;
use Simplon\Core\Utils\Config;
use Simplon\Core\Utils\EventsHandler;
use Simplon\Locale\Readers\PhpFileReader;

/**
 * Interface AppContextInterface
 * @package Simplon\Core\Interfaces
 */
interface AppContextInterface
{
    /**
     * @return array
     */
    public function getLocalePaths(): array;

    /**
     * @param array $paths
     *
     * @return PhpFileReader
     */
    public function getLocaleFileReader(array $paths): PhpFileReader;

    /**
     * @return Config
     */
    public function getConfig(): Config;

    /**
     * @return EventsHandler
     */
    public function getEventsHandler(): EventsHandler;

    /**
     * @return SessionStorage
     */
    public function getSessionStorage(): SessionStorage;
}