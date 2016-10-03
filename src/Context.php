<?php

namespace Simplon\Core;

use Simplon\Core\Storage\SessionStorage;
use Simplon\Core\Utils\Config;
use Simplon\Core\Utils\EventsHandler;
use Simplon\Locale\Readers\PhpFileReader;

/**
 * Class Context
 * @package Simplon\Core
 */
class Context
{
    const APP_PATH = __DIR__ . '/../../../../src';

    /**
     * @var Config
     */
    private static $config;
    /**
     * @var Config[]
     */
    private static $configCache;
    /**
     * @var SessionStorage
     */
    private static $sessionStorage;
    /**
     * @var EventsHandler
     */
    private static $eventsHandler;

    /**
     * @param array $paths
     *
     * @return PhpFileReader
     */
    public static function getLocaleFileReader(array $paths): PhpFileReader
    {
        return new PhpFileReader($paths);
    }

    /**
     * @param string $workingDir
     * @param array $keys
     *
     * @return Config
     */
    public static function getConfig(string $workingDir, array $keys = []): Config
    {
        if (!self::$config)
        {
            self::$config = new Config(require self::APP_PATH . '/Configs/config.php');

            if (getenv('APP_ENV') === 'production')
            {
                if (file_exists(self::APP_PATH . '/Configs/production.php'))
                {
                    self::$config->addConfig(require self::APP_PATH . '/Configs/production.php');
                }
            }
        }

        $md5WorkingDir = md5($workingDir);

        if (empty(self::$configCache[$md5WorkingDir]))
        {
            if (file_exists($workingDir . '/Configs/config.php'))
            {
                /** @noinspection PhpIncludeInspection */
                self::$config->addConfig(require $workingDir . '/Configs/config.php');

                if (getenv('APP_ENV') === 'production')
                {
                    if (file_exists($workingDir . '/Configs/production.php'))
                    {
                        /** @noinspection PhpIncludeInspection */
                        self::$config->addConfig(require $workingDir . '/Configs/production.php');
                    }
                }
            }

            self::$configCache[$md5WorkingDir] = self::$config;
        }

        if (empty($key))
        {
            return self::$configCache[$md5WorkingDir];
        }

        return self::$configCache[$md5WorkingDir]->get($key);
    }

    /**
     * @return EventsHandler
     */
    public static function getEventsHandler(): EventsHandler
    {
        if (!self::$eventsHandler)
        {
            self::$eventsHandler = new EventsHandler();
        }

        return self::$eventsHandler;
    }

    /**
     * @return SessionStorage
     */
    public static function getSessionStorage(): SessionStorage
    {
        if (!self::$sessionStorage)
        {
            self::$sessionStorage = new SessionStorage();
        }

        return self::$sessionStorage;
    }
}