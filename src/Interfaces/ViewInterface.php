<?php

namespace Simplon\Core\Interfaces;

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
     * @return Locale
     */
    public function getLocale(): Locale;

    /**
     * @param Locale $locale
     *
     * @return static
     */
    public function setLocale(Locale $locale);

    /**
     * @return FlashMessage
     */
    public function getFlashMessage(): FlashMessage;

    /**
     * @param FlashMessage $flashMessage
     *
     * @return static
     */
    public function setFlashMessage(FlashMessage $flashMessage);

    /**
     * @return Device
     */
    public function getDevice(): Device;

    /**
     * @param Device $device
     *
     * @return static
     */
    public function setDevice(Device $device);

    /**
     * @param array|null $globalData
     *
     * @return string
     */
    public function render(array $globalData = null): string;
}