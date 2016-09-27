<?php

namespace Simplon\Core\Data;

use Simplon\Core\Interfaces\ResponseDataInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ResponseRestData
 * @package Simplon\Core\Data
 */
class ResponseRestData implements ResponseDataInterface
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return ResponseInterface
     */
    public function render(): ResponseInterface
    {
        $this->response = $this->response->withAddedHeader('Content-Type', 'application/json; charset=UTF-8');

        return $this->response;
    }
}