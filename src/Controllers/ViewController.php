<?php

namespace Simplon\Core\Controllers;

use Simplon\Core\Data\ResponseViewData;
use Simplon\Core\Data\CoreViewData;
use Simplon\Core\Interfaces\ComponentContextInterface;
use Simplon\Core\Interfaces\CoreContextInterface;
use Simplon\Core\Interfaces\RegistryInterface;
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
     *
     * @return ResponseViewData
     */
    public function respond(ViewInterface $view): ResponseViewData
    {
        $this->getResponse()->getBody()->write($view->render());

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
     * @return CoreViewData
     */
    public function getCoreViewData(): CoreViewData
    {
        return new CoreViewData($this->getLocale(), $this->getFlashMessage(), $this->getDevice());
    }

    /**
     * @return FlashMessage
     */
    public function getFlashMessage(): FlashMessage
    {
        if (!$this->flashMessage)
        {
            /** @var RegistryInterface $registry */
            $registry = $this->getRegistry();

            /** @var ComponentContextInterface $context */
            $context = $registry->getContext();

            /** @var CoreContextInterface $appContext */
            $appContext = $context->getAppContext();

            /** @noinspection PhpUndefinedMethodInspection */
            $this->flashMessage = new FlashMessage($appContext->getSessionStorage());
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