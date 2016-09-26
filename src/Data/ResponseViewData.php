<?php

namespace Simplon\Core\Data;

use Simplon\Core\Interfaces\ResponseDataInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ResponseViewData
 * @package Simplon\Core\Data
 */
class ResponseViewData implements ResponseDataInterface
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
        return $this->response;
    }
}