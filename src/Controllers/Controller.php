<?php

namespace Simplon\Core\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\CoreContext;
use Simplon\Core\Data\ControllerCoreData;
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
     * @var ControllerCoreData
     */
    protected $coreData;
    /**
     * @see exect type will be refered in controller due to component dependency
     */
    protected $context;
    /**
     * @var Locale
     */
    protected $locale;

    /**
     * @param ControllerCoreData $coreData
     */
    public function __construct(ControllerCoreData $coreData)
    {
        $this->coreData = $coreData;
        $this->context = $this->coreData->getContext();
    }

    /**
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->coreData->getRequest();
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->coreData->getResponse();
    }

    /**
     * @return string
     */
    public function getWorkingDir(): string
    {
        return $this->coreData->getWorkingDir();
    }

    /**
     * @return Locale
     */
    public function getLocale(): Locale
    {
        if (!$this->locale)
        {
            /** @var ComponentContextInterface $context */
            $context = $this->context;
            $this->locale = new Locale($context->getAppContext()->getLocaleFileReader($this->getLocalePaths()), [LocaleMiddleware::getLocaleCode()]);
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