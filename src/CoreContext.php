<?php

namespace Simplon\Core;

use Simplon\Core\Interfaces\CoreContextInterface;
use Simplon\Core\Middleware\LocaleMiddleware;
use Simplon\Core\Storage\CookieStorage;
use Simplon\Core\Storage\SessionStorage;
use Simplon\Core\Utils\Config;
use Simplon\Core\Utils\EventsHandler;
use Simplon\Locale\Locale;
use Simplon\Locale\Readers\PhpFileReader;

/**
 * Class CoreContext
 * @package Simplon\Core
 */
abstract class CoreContext implements CoreContextInterface
{
    const APP_PATH = __DIR__ . '/../../../../src';
    const APP_ENV_DEV = 'dev';
    const APP_ENV_REVIEW = 'review';
    const APP_ENV_STAGE = 'stage';
    const APP_ENV_PRODUCTION = 'production';

    /**
     * @var Config
     */
    protected $config;
    /**
     * @var Locale
     */
    protected $locale;
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
     * @return array
     */
    public function getRemoteEnvs(): array
    {
        return [
            self::APP_ENV_REVIEW,
            self::APP_ENV_STAGE,
            self::APP_ENV_PRODUCTION,
        ];
    }

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
     * @param null|string $workingDir
     *
     * @return Config
     */
    public function getConfig(?string $workingDir = null): Config
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
     * @param null|string $workingDir
     *
     * @return Locale
     */
    public function getLocale(?string $workingDir = null): Locale
    {
        if (!$this->locale)
        {
            $paths = $this->getLocalePaths();

            if ($workingDir)
            {
                $paths[] = rtrim($workingDir, '/') . '/Locales';
            }

            $this->locale = new Locale($this->getLocaleFileReader($paths), [LocaleMiddleware::getLocaleCode()]);
            $this->locale->setLocale(LocaleMiddleware::getLocaleCode());
        }

        return $this->locale;
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
     * @return array
     */
    protected function getLocalePaths(): array
    {
        return [
            self::APP_PATH . '/Locales',
        ];
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

            foreach ($this->getRemoteEnvs() as $env)
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