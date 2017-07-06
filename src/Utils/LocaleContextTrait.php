<?php

namespace Simplon\Core\Utils;

use Simplon\Core\CoreContext;
use Simplon\Core\Middleware\LocaleMiddleware;
use Simplon\Helper\Data\InstanceData;
use Simplon\Helper\Instances;
use Simplon\Locale\Locale;
use Simplon\Locale\Readers\PhpFileReader;

/**
 * Trait LocaleContextTrait
 * @package Simplon\Core\Utils
 */
trait LocaleContextTrait
{
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
            ->addParam($this->getLocaleFileReader($paths))
            ->addParam([LocaleMiddleware::getLocaleCode()])
            ->setAfterCallback(function (Locale $locale) {
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
}