<?php

namespace Simplon\Core\Controllers;

use Psr\Http\Message\ResponseInterface;
use Simplon\Core\Components\Context;
use Simplon\Core\CoreContext;
use Simplon\Core\Data\CoreViewData;
use Simplon\Core\Data\ResponseViewData;
use Simplon\Core\Interfaces\RegistryInterface;
use Simplon\Core\Interfaces\ViewInterface;
use Simplon\Core\Utils\CoreDevice;
use Simplon\Core\Views\FlashMessage;
use Simplon\Device\Device;

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
     * @param null|ResponseInterface $response
     *
     * @return ResponseViewData
     */
    public function respond(ViewInterface $view, ?ResponseInterface $response = null): ResponseViewData
    {
        if (!$response)
        {
            $response = $this->getResponse();
        }

        $response->getBody()->write($view->render());

        return new ResponseViewData($response);
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
            $this->getResponse()->withStatus($code)->withHeader('Location', (string)$url)
        );
    }

    /**
     * @return CoreViewData
     * @throws \Exception
     */
    public function getCoreViewData(): CoreViewData
    {
        /** @var RegistryInterface $registry */
        $registry = $this->getRegistry();

        /** @var CoreContext $context */
        $context = $registry->getContext();

        return new CoreViewData($context->getLocale(), $this->getFlashMessage(), $this->getDevice());
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

            /** @var Context $context */
            $context = $registry->getContext();

            /** @var CoreContext $appContext */
            $appContext = $context->getAppContext();

            /** @noinspection PhpUndefinedMethodInspection */
            $this->flashMessage = new FlashMessage($appContext->getSessionStorage());
        }

        return $this->flashMessage;
    }

    /**
     * @return Device
     * @throws \Exception
     */
    public function getDevice(): Device
    {
        if (!$this->device)
        {
            $this->device = new CoreDevice($this->getUserAgent());
        }

        return $this->device;
    }

    /**
     * @return null|string
     */
    protected function getUserAgent(): ?string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? null;
    }
}