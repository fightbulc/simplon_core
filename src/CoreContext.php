<?php

namespace Simplon\Core;

use Simplon\Core\Interfaces\CoreContextInterface;
use Simplon\Core\Storage\SessionStorage;
use Simplon\Core\Utils\Config;
use Simplon\Core\Utils\EventsHandler;
use Simplon\Locale\Readers\PhpFileReader;

/**
 * Class CoreContext
 * @package Simplon\Core
 */
abstract class CoreContext implements CoreContextInterface
{
    const APP_PATH = __DIR__ . '/../../../../src';

    /**
     * @var Config
     */
    private $config;
    /**
     * @var Config[]
     */
    private $configCache;
    /**
     * @var SessionStorage
     */
    private $sessionStorage;
    /**
     * @var EventsHandler
     */
    private $eventsHandler;

    /**
     * @param array $paths
     *
     * @return PhpFileReader
     */
    public function getLocaleFileReader(array $paths): PhpFileReader
    {
        return new PhpFileReader($paths);
    }

    /**
     * @param string $workingDir
     *
     * @return Config
     */
    public function getConfig(string $workingDir): Config
    {
        if (!$this->config)
        {
            /** @noinspection PhpIncludeInspection */
            $this->config = new Config(require self::APP_PATH . '/Configs/config.php');

            if (getenv('APP_ENV') === 'production')
            {
                if (file_exists(self::APP_PATH . '/Configs/production.php'))
                {
                    /** @noinspection PhpIncludeInspection */
                    $this->config->addConfig(require self::APP_PATH . '/Configs/production.php');
                }
            }
        }

        $md5WorkingDir = md5($workingDir);

        if (empty($this->configCache[$md5WorkingDir]))
        {
            if (file_exists($workingDir . '/Configs/config.php'))
            {
                /** @noinspection PhpIncludeInspection */
                $this->config->addConfig(require $workingDir . '/Configs/config.php');

                if (getenv('APP_ENV') === 'production')
                {
                    if (file_exists($workingDir . '/Configs/production.php'))
                    {
                        /** @noinspection PhpIncludeInspection */
                        $this->config->addConfig(require $workingDir . '/Configs/production.php');
                    }
                }
            }

            $this->configCache[$md5WorkingDir] = $this->config;
        }

        return $this->configCache[$md5WorkingDir];
    }

    /**
     * @return EventsHandler
     */
    public function getEventsHandler(): EventsHandler
    {
        if (!$this->eventsHandler)
        {
            $this->eventsHandler = new EventsHandler();
        }

        return $this->eventsHandler;
    }

    /**
     * @return SessionStorage
     */
    public function getSessionStorage(): SessionStorage
    {
        if (!$this->sessionStorage)
        {
            $this->sessionStorage = new SessionStorage();
        }

        return $this->sessionStorage;
    }
}