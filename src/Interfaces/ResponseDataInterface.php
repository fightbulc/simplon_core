<?php

namespace Simplon\Core\Interfaces;

use Psr\Http\Message\ResponseInterface;

interface ResponseDataInterface
{
    /**
     * @return ResponseInterface
     */
    public function render(): ResponseInterface;
}