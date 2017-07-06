<?php

namespace Simplon\Core;

use Simplon\Core\Interfaces\CoreContextInterface;
use Simplon\Core\Storage\CookieStorage;
use Simplon\Core\Storage\SessionStorage;
use Simplon\Core\Utils\Config;
use Simplon\Core\Utils\EventsHandler;
use Simplon\Core\Utils\LocaleContextTrait;
use Simplon\Helper\Data\InstanceData;
use Simplon\Helper\Instances;

/**
 * Class CoreContext
 * @package Simplon\Core
 */
abstract class CoreContext implements CoreContextInterface
{
    use LocaleContextTrait;

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
     * @var Config[]
     */
    protected $configCache;

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
     * @return EventsHandler
     */
    public function getEventsHandler(): EventsHandler
    {
        $instanceData = InstanceData::create(EventsHandler::class);

        return Instances::cache($instanceData);
    }

    /**
     * @return SessionStorage
     */
    public function getSessionStorage(): SessionStorage
    {
        $instanceData = InstanceData::create(SessionStorage::class);

        return Instances::cache($instanceData);
    }

    /**
     * @return CookieStorage
     */
    public function getCookieStorage(): CookieStorage
    {
        $instanceData = InstanceData::create(CookieStorage::class)->addParam($this->getCookieStorageNameSpace());

        return Instances::cache($instanceData);
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

    /**
     * @return string
     */
    protected function getCookieStorageNameSpace(): string
    {
        return 'CORE';
    }
}