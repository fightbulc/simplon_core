<?php

namespace Simplon\Core\Interfaces;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface ResponseDataInterface
 * @package Simplon\Core\Interfaces
 */
interface ResponseDataInterface
{
    /**
     * @return ResponseInterface
     */
    public function render(): ResponseInterface;
}