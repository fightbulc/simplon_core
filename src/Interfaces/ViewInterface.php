<?php

namespace Simplon\Core\Interfaces;

use Simplon\Core\Data\ViewInitialData;
use Simplon\Core\Views\FlashMessage;
use Simplon\Device\Device;
use Simplon\Locale\Locale;

/**
 * Interface ViewInterface
 * @package Simplon\Core\Interfaces
 */
interface ViewInterface
{
    /**
     * @return array
     */
    public function getAssetsCss(): array;

    /**
     * @return array
     */
    public function getAssetsJs(): array;

    /**
     * @return array
     */
    public function getAssetsCode(): array;

    /**
     * @return array
     */
    public function getGlobalData(): array;

    /**
     * @param array|null $globalData
     *
     * @return string
     */
    public function render(array $globalData = null): string;

    /**
     * @return ViewInitialData
     */
    public function getViewInitialData(): ViewInitialData;

    /**
     * @return Locale
     */
    public function getLocale(): Locale;

    /**
     * @return FlashMessage
     */
    public function getFlashMessage(): FlashMessage;

    /**
     * @return Device
     */
    public function getDevice(): Device;
}