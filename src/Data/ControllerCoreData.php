<?php

namespace Simplon\Core\Data;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\Interfaces\ComponentContextInterface;

/**
 * @package Simplon\Core\Data
 */
class ControllerCoreData extends Data
{
    /**
     * @var ServerRequestInterface
     */
    private $request;
    /**
     * @var ResponseInterface
     */
    private $response;
    /**
     * @var ComponentContextInterface
     */
    private $context;
    /**
     * @var string
     */
    private $workingDir;

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param ComponentContextInterface $context
     * @param string $workingDir
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response, ComponentContextInterface $context, string $workingDir)
    {
        $this->request = $request;
        $this->response = $response;
        $this->context = $context;
        $this->workingDir = $workingDir;
    }

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
     * @return ComponentContextInterface
     */
    public function getContext(): ComponentContextInterface
    {
        return $this->context;
    }

    /**
     * @return string
     */
    public function getWorkingDir(): string
    {
        return $this->workingDir;
    }
}