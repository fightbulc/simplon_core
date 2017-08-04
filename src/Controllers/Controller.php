<?php

namespace Simplon\Core\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\Components\Context;
use Simplon\Core\CoreContext;
use Simplon\Core\Events\Events;
use Simplon\Core\Interfaces\ControllerInterface;
use Simplon\Core\Interfaces\RegistryInterface;
use Simplon\Core\Utils\Config;
use Simplon\Core\Utils\Form\BaseForm;
use Simplon\Core\Utils\Form\FormWrapper;
use Simplon\Locale\Locale;

/**
 * Class Controller
 * @package Simplon\Core\Controllers
 */
abstract class Controller implements ControllerInterface
{
    protected $registry;
    /**
     * @var ServerRequestInterface
     */
    protected $request;
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @param $registry
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    public function __construct($registry, ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->registry = $registry;
        $this->request = $request;
        $this->response = $response;
    }

    abstract public function getRegistry();

    /**
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        /** @var RegistryInterface $registry */
        $registry = $this->registry;

        /** @var Context $context */
        $context = $registry->getContext();

        return $context->getConfig();
    }

    /**
     * @return Locale
     */
    public function getLocale(): Locale
    {
        /** @var RegistryInterface $registry */
        $registry = $this->registry;

        /** @var Context $context */
        $context = $registry->getContext();

        return $context->getLocale();
    }

    /**
     * @return Events
     */
    public function getEvents(): Events
    {
        /** @var RegistryInterface $registry */
        $registry = $this->registry;

        /** @var Context $context */
        $context = $registry->getContext();

        /** @var CoreContext $appContext */
        $appContext = $context->getAppContext();

        return $appContext->getEvents();
    }

    /**
     * @param BaseForm $form
     * @param array $initialData
     *
     * @return FormWrapper
     */
    public function buildFormWrapper(BaseForm $form, array $initialData = []): FormWrapper
    {
        return new FormWrapper($form, $this->getRequestBody(), $initialData);
    }

    /**
     * @return array|null|object
     */
    public function getRequestBody()
    {
        return $this->getRequest()->getParsedBody();
    }
}