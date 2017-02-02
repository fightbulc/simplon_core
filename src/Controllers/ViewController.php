<?php

namespace Simplon\Core\Controllers;

use Simplon\Core\CoreContext;
use Simplon\Core\Data\ResponseViewData;
use Simplon\Core\Interfaces\CoreContextInterface;
use Simplon\Core\Interfaces\ViewInterface;
use Simplon\Core\Middleware\LocaleMiddleware;
use Simplon\Core\Views\FlashMessage;
use Simplon\Device\Device;
use Simplon\Locale\Locale;

/**
 * Class ViewController
 * @package Simplon\Core\Controllers
 */
abstract class ViewController extends Controller
{
    /**
     * @var Locale
     */
    protected $locale;
    /**
     * @var FlashMessage
     */
    protected $flashMessage;
    /**
     * @var Device
     */
    protected $device;

    /**
     * @param array $params
     *
     * @return ResponseViewData
     */
    abstract public function __invoke(array $params): ResponseViewData;

    /**
     * @param ViewInterface $view
     * @param array $globalData
     *
     * @return ResponseViewData
     */
    public function respond(ViewInterface $view, array $globalData = []): ResponseViewData
    {
        $view
            ->setLocale($this->getLocale())
            ->setFlashMessage($this->getFlashMessage())
            ->setDevice($this->getDevice())
        ;

        $this->getResponse()->getBody()->write($view->render($globalData));

        return new ResponseViewData($this->getResponse());
    }

    /**
     * @param string $url
     * @param int $code
     *
     * @return ResponseViewData
     */
    public function redirect(string $url, int $code = 301): ResponseViewData
    {
        return new ResponseViewData(
            $this->getResponse()->withStatus($code)->withHeader('Location', $url)
        );
    }

    /**
     * @return Locale
     */
    public function getLocale(): Locale
    {
        if (!$this->locale)
        {
            $this->locale = new Locale($this->getAppContext()->getLocaleFileReader($this->getLocalePaths()), [LocaleMiddleware::getLocaleCode()]);
            $this->locale->setLocale(LocaleMiddleware::getLocaleCode());
        }

        return $this->locale;
    }

    /**
     * @return FlashMessage
     */
    public function getFlashMessage(): FlashMessage
    {
        if (!$this->flashMessage)
        {
            $this->flashMessage = new FlashMessage(
                $this->getAppContext()->getSessionStorage()
            );
        }

        return $this->flashMessage;
    }

    /**
     * @return Device
     */
    public function getDevice(): Device
    {
        if (!$this->device)
        {
            $this->device = new Device();
        }

        return $this->device;
    }

    /**
     * @return array
     */
    protected function getLocalePaths(): array
    {
        return [
            CoreContext::APP_PATH . '/Locales', // app path
            $this->getWorkingDir() . '/Locales', // component path
        ];
    }

    /**
     * @return CoreContextInterface
     */
    private function getAppContext()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return $this->context->getAppContext();
    }
}