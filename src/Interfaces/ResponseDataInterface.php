<?php

namespace Core\Interfaces;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface ResponseDataInterface
 * @package Core\Interfaces
 */
interface ResponseDataInterface
{
    /**
     * @return ResponseInterface
     */
    public function render(): ResponseInterface;
}