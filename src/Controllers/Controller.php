<?php

namespace Simplon\Core\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\CoreContext;
use Simplon\Core\Interfaces\ControllerInterface;
use Simplon\Core\Interfaces\CoreContextInterface;
use Simplon\Core\Middleware\LocaleMiddleware;
use Simplon\Locale\Locale;

/**
 * Class Controller
 * @package Simplon\Core\Controllers
 */
abstract class Controller implements ControllerInterface
{
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
     * @param $context
     *
     * @return ControllerInterface
     */
    public function setContext($context): ControllerInterface
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
            $this->locale = new Locale($this->getAppContext()->getLocaleFileReader($this->getLocalePaths()), [LocaleMiddleware::getLocaleCode()]);
            $this->locale->setLocale(LocaleMiddleware::getLocaleCode());
        }

        return $this->locale;
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
    protected function getAppContext()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return $this->context->getAppContext();
    }
}