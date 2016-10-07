<?php

namespace Simplon\Core\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplon\Core\Interfaces\ControllerInterface;

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
}