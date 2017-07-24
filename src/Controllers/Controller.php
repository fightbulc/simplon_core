<?php

namespace Simplon\Core\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\Components\Context;
use Simplon\Core\CoreContext;
use Simplon\Core\Interfaces\ControllerInterface;
use Simplon\Core\Interfaces\RegistryInterface;
use Simplon\Core\Utils\EventsHandler;

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
     * @return EventsHandler
     */
    public function getEvents(): EventsHandler
    {
        /** @var RegistryInterface $registry */
        $registry = $this->registry;

        /** @var Context $context */
        $context = $registry->getContext();

        /** @var CoreContext $appContext */
        $appContext = $context->getAppContext();

        return $appContext->getEventsHandler();
    }
}