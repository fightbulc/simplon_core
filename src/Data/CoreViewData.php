<?php

namespace Simplon\Core\Data;

use Simplon\Core\Views\FlashMessage;
use Simplon\Device\Device;
use Simplon\Helper\Data\Data;
use Simplon\Locale\Locale;

/**
 * @package Simplon\Core\Data
 */
class CoreViewData extends Data
{
    /**
     * @var Locale
     */
    private $locale;
    /**
     * @var FlashMessage
     */
    private $flashMessage;
    /**
     * @var Device
     */
    private $device;

    /**
     * @param Locale $locale
     * @param FlashMessage $flashMessage
     * @param Device $device
     */
    public function __construct(Locale $locale, FlashMessage $flashMessage, Device $device)
    {
        $this->locale = $locale;
        $this->flashMessage = $flashMessage;
        $this->device = $device;
    }

    /**
     * @return Locale
     */
    public function getLocale(): Locale
    {
        return $this->locale;
    }

    /**
     * @return FlashMessage
     */
    public function getFlashMessage(): FlashMessage
    {
        return $this->flashMessage;
    }

    /**
     * @return Device
     */
    public function getDevice(): Device
    {
        return $this->device;
    }
}