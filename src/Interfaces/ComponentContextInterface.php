<?php

namespace Simplon\Core\Interfaces;

use Simplon\Core\Utils\Config;
use Simplon\Locale\Locale;

/**
 * Interface ComponentContextInterface
 * @package Simplon\Core\Interfaces
 */
interface ComponentContextInterface
{
    public function getAppContext();

    /**
     * @return null|Config
     */
    public function getConfig(): ?Config;

    /**
     * @return null|Locale
     */
    public function getLocale(): ?Locale;
}