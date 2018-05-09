<?php

namespace Simplon\Core\Interfaces;

use Simplon\Core\Data\CoreViewData;
use Simplon\Core\Views\FlashMessage;
use Simplon\Device\DeviceInterface;
use Simplon\Locale\Locale;

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
     * @return CoreViewData
     */
    public function getCoreViewData(): CoreViewData;

    /**
     * @return Locale
     */
    public function getLocale(): Locale;

    /**
     * @return FlashMessage
     */
    public function getFlashMessage(): FlashMessage;

    /**
     * @return DeviceInterface
     */
    public function getDevice(): DeviceInterface;

    /**
     * @return string
     */
    public function render(): string;
}