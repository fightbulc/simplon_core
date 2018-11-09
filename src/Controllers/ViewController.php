<?php

namespace Simplon\Core\Controllers;

use Psr\Http\Message\ResponseInterface;
use Simplon\Core\Components\Context;
use Simplon\Core\CoreContext;
use Simplon\Core\Data\CoreViewData;
use Simplon\Core\Data\ResponseViewData;
use Simplon\Core\Interfaces\RegistryInterface;
use Simplon\Core\Interfaces\ViewInterface;
use Simplon\Core\Views\FlashMessage;
use Simplon\Device\Device;
use Simplon\Device\DeviceInterface;
use Simplon\Interfaces\StorageInterface;

abstract class ViewController extends Controller
{
    /**
     * @var FlashMessage
     */
    protected $flashMessage;
    /**
     * @var DeviceInterface
     */
    protected $device;

    /**
     * @param array $params
     *
     * @return ResponseViewData
     */
    abstract public function __invoke(array $params): ResponseViewData;

    /**
     * @param ViewInterface          $view
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
     * @param int    $code
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
            /** @noinspection PhpUndefinedMethodInspection */
            $this->flashMessage = new FlashMessage($this->getSessionStorage());
        }

        return $this->flashMessage;
    }

    /**
     * @return DeviceInterface
     * @throws \Exception
     */
    public function getDevice(): DeviceInterface
    {
        if (!$this->device)
        {
            $this->device = new Device($this->getUserAgent());

            if (getenv('USE_DEVICE_SESSION') === true)
            {
                $this->device->setStorage($this->getSessionStorage()); // to cache computed results
            }
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

    /**
     * @return StorageInterface
     */
    private function getSessionStorage(): StorageInterface
    {
        /** @var RegistryInterface $registry */
        $registry = $this->getRegistry();

        /** @var Context $context */
        $context = $registry->getContext();

        /** @var CoreContext $appContext */
        $appContext = $context->getAppContext();

        return $appContext->getSessionStorage();
    }
}