<?php

namespace Simplon\Core\Interfaces;

use Simplon\Core\Storage\SessionStorage;
use Simplon\Core\Utils\Config;
use Simplon\Core\Utils\EventsHandler;
use Simplon\Locale\Locale;
use Simplon\Locale\Readers\PhpFileReader;

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
     * @param array $paths
     *
     * @return PhpFileReader
     */
    public function getLocaleFileReader(array $paths): PhpFileReader;

    /**
     * @param null|string $workingDir
     *
     * @return Config
     */
    public function getConfig(?string $workingDir = null): Config;

    /**
     * @param null|string $workingDir
     *
     * @return Locale
     */
    public function getLocale(?string $workingDir = null): Locale;

    /**
     * @return EventsHandler
     */
    public function getEventsHandler(): EventsHandler;

    /**
     * @return SessionStorage
     */
    public function getSessionStorage(): SessionStorage;
}