<?php

namespace Simplon\Core\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface ControllerInterface
 * @package Simplon\Core\Interfaces
 */
interface ControllerInterface
{
    /**
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface;

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface;

    /**
     * @see exect type will be refered in controller due to component dependency
     */
    public function getContext();

    /**
     * @return string
     */
    public function getWorkingDir(): string;
}