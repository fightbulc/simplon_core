<?php

namespace Simplon\Core;

use Simplon\Core\Events\Events;
use Simplon\Core\Middleware\LocaleMiddleware;
use Simplon\Core\Storage\CookieStorage;
use Simplon\Core\Storage\SessionStorage;
use Simplon\Core\Utils\Config;
use Simplon\Helper\Data\InstanceData;
use Simplon\Helper\Instances;
use Simplon\Locale\Locale;
use Simplon\Locale\Readers\PhpFileReader;

abstract class CoreContext
{
    const APP_PATH = __DIR__ . '/../../../../src';

    const APP_ENV_DEV = 'dev';
    const APP_ENV_REVIEW = 'review';
    const APP_ENV_STAGE = 'stage';
    const APP_ENV_PROD = 'production';
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
     * @return Events
     */
    public function getEvents(): Events
    {
        $instanceData = InstanceData::create(Events::class);

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
        $instanceData = InstanceData::create(CookieStorage::class);

        return Instances::cache($instanceData);
    }

    /**
     * @param null|string $workingDir
     *
     * @return Locale
     */
    public function getLocale(?string $workingDir = null): Locale
    {
        $paths = $this->getAppLocalePaths();

        if ($workingDir)
        {
            $paths = array_merge($paths, $this->getComponentLocalePaths($workingDir));
        }

        $instanceData = InstanceData::create(Locale::class);

        $instanceData
            ->setCacheName(Locale::class . '-' . md5(json_encode($paths)))
            ->addParam($this->getLocaleFileReader($paths))
            ->addParam([LocaleMiddleware::getLocaleCode()])
            ->setAfterCallback(function (Locale $locale)
            {
                return $locale->setLocale(LocaleMiddleware::getLocaleCode());
            })
        ;

        return Instances::cache($instanceData);
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
     * @return array
     */
    protected function getAppLocalePaths(): array
    {
        return [
            CoreContext::APP_PATH . '/Locales',
        ];
    }

    /**
     * @param string $workingDir
     *
     * @return array
     */
    protected function getComponentLocalePaths(string $workingDir): array
    {
        return [
            rtrim($workingDir, '/') . '/Locales',
        ];
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