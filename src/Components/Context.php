<?php

namespace Simplon\Core\Components;

use App\AppContext;
use Simplon\Core\Utils\Config;
use Simplon\Locale\Locale;

abstract class Context
{
    /**
     * @var string
     */
    protected $componentDir;
    /**
     * @var AppContext
     */
    protected $appContext;

    /**
     * @param AppContext $appContext
     */
    public function __construct(AppContext $appContext)
    {
        $this->appContext = $appContext;
    }

    /**
     * @return AppContext
     */
    public function getAppContext(): AppContext
    {
        return $this->appContext;
    }

    /**
     * @return null|Config
     */
    public function getConfig(): ?Config
    {
        return $this->getAppContext()->getConfig($this->getComponentDir());
    }

    /**
     * @return null|Locale
     */
    public function getLocale(): ?Locale
    {
        return $this->getAppContext()->getLocale($this->getComponentDir());
    }

    /**
     * @return string
     */
    protected function getComponentDir(): string
    {
        if (!$this->componentDir)
        {
            $this->componentDir = preg_replace('/\/\w+\.php$/', '', (new \ReflectionClass(static::class))->getFileName());
        }

        return $this->componentDir;
    }
}