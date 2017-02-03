<?php

namespace Simplon\Core\Controllers;

use Simplon\Core\Data\ResponseViewData;
use Simplon\Core\Data\ViewInitialData;
use Simplon\Core\Interfaces\ViewInterface;
use Simplon\Core\Views\FlashMessage;
use Simplon\Device\Device;

/**
 * Class ViewController
 * @package Simplon\Core\Controllers
 */
abstract class ViewController extends Controller
{
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
     * @return ViewInitialData
     */
    public function getViewInitialData(): ViewInitialData
    {
        return new ViewInitialData($this->getLocale(), $this->getFlashMessage(), $this->getDevice());
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
}