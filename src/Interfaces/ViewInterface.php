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

    /**
     * @return string
     */
    public function render(): string;
}