<?php

namespace Simplon\Core\Interfaces;

use Simplon\Core\Utils\Config;

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
}