<?php

namespace Simplon\Core;

use Simplon\Core\Interfaces\CoreContextInterface;
use Simplon\Core\Storage\CookieStorage;
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
    const APP_ENV_DEV = 'dev';
    const APP_ENV_STAGING = 'staging';
    const APP_ENV_PRODUCTION = 'production';

    /**
     * @var Config
     */
    protected $config;
    /**
     * @var Config[]
     */
    protected $configCache;
    /**
     * @var SessionStorage
     */
    protected $sessionStorage;
    /**
     * @var CookieStorage
     */
    protected $cookieStorage;
    /**
     * @var EventsHandler
     */
    protected $eventsHandler;

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
    public function getConfig(string $workingDir = null): Config
    {
        if (!$this->config)
        {
            $this->config = $this->searchAddConfigByPath(new Config(), self::APP_PATH . '/Configs');
        }

        if ($workingDir)
        {
            $md5WorkingDir = md5($workingDir);

            if (empty($this->configCache[$md5WorkingDir]))
            {
                $this->configCache[$md5WorkingDir] = $this->searchAddConfigByPath($this->config, $workingDir . '/Configs');
            }

            return $this->configCache[$md5WorkingDir];
        }

        return $this->config;
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

    /**
     * @return CookieStorage
     */
    public function getCookieStorage(): CookieStorage
    {
        if (!$this->cookieStorage)
        {
            $this->cookieStorage = new CookieStorage($this->getCookieStorageNameSpace());
        }

        return $this->cookieStorage;
    }

    /**
     * @return string
     */
    protected function getCookieStorageNameSpace(): string
    {
        return 'CORE';
    }

    /**
     * @param Config $config
     * @param string $path
     *
     * @return Config
     */
    protected function searchAddConfigByPath(Config $config, string $path): Config
    {
        if (file_exists($path . '/config.php'))
        {
            /** @noinspection PhpIncludeInspection */
            $config->addConfig(require $path . '/config.php');

            foreach ([self::APP_ENV_STAGING, self::APP_ENV_PRODUCTION] as $env)
            {
                if (getenv('APP_ENV') === $env)
                {
                    $envFilePath = $path . '/' . $env . '.php';

                    if (file_exists($envFilePath))
                    {
                        /** @noinspection PhpIncludeInspection */
                        $config->addConfig(require $envFilePath);
                    }

                    break;
                }
            }
        }

        return $config;
    }
}