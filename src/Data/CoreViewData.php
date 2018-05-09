<?php

namespace Simplon\Core\Data;

use Simplon\Core\Views\FlashMessage;
use Simplon\Device\DeviceInterface;
use Simplon\Locale\Locale;

class CoreViewData
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
     * @var DeviceInterface
     */
    private $device;

    /**
     * @param Locale          $locale
     * @param FlashMessage    $flashMessage
     * @param DeviceInterface $device
     */
    public function __construct(Locale $locale, FlashMessage $flashMessage, DeviceInterface $device)
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
     * @return DeviceInterface
     */
    public function getDevice(): DeviceInterface
    {
        return $this->device;
    }
}