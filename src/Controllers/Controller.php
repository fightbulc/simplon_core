<?php

namespace Simplon\Core\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\CoreContext;
use Simplon\Core\Interfaces\ComponentContextInterface;
use Simplon\Core\Interfaces\ControllerInterface;
use Simplon\Core\Middleware\LocaleMiddleware;
use Simplon\Core\Utils\EventsHandler;
use Simplon\Locale\Locale;

/**
 * Class Controller
 * @package Simplon\Core\Controllers
 */
abstract class Controller implements ControllerInterface
{
    /**
     * @see ViewController defines actual type
     */
    protected $context;
    /**
     * @var ServerRequestInterface
     */
    protected $request;
    /**
     * @var ResponseInterface
     */
    protected $response;
    /**
     * @var string
     */
    protected $workingDir;
    /**
     * @var Locale
     */
    protected $locale;

    /**
     * @param ComponentContextInterface $context
     *
     * @return ControllerInterface
     */
    public function setContext(ComponentContextInterface $context): ControllerInterface
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ControllerInterface
     */
    public function setRequest(ServerRequestInterface $request): ControllerInterface
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return ControllerInterface
     */
    public function setResponse(ResponseInterface $response): ControllerInterface
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return string
     */
    public function getWorkingDir(): string
    {
        return $this->workingDir;
    }

    /**
     * @param string $workingDir
     *
     * @return ControllerInterface
     */
    public function setWorkingDir(string $workingDir): ControllerInterface
    {
        $this->workingDir = $workingDir;

        return $this;
    }

    /**
     * @return Locale
     */
    public function getLocale(): Locale
    {
        if (!$this->locale)
        {
            /** @noinspection PhpUndefinedMethodInspection */
            $this->locale = new Locale($this->context->getAppContext()->getLocaleFileReader($this->getLocalePaths()), [LocaleMiddleware::getLocaleCode()]);
            $this->locale->setLocale(LocaleMiddleware::getLocaleCode());
        }

        return $this->locale;
    }

    /**
     * @return EventsHandler
     */
    public function getEventsHandler(): EventsHandler
    {
        /** @var ComponentContextInterface $context */
        $context = $this->context;

        return $context->getAppContext()->getEventsHandler();
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
}