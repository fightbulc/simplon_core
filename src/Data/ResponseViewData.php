<?php

namespace Simplon\Core\Data;

use Psr\Http\Message\ResponseInterface;
use Simplon\Core\Interfaces\ResponseDataInterface;

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
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return bool
     */
    public function hasRedirect(): bool
    {
        return empty($this->response->getHeaderLine('Location')) === false;
    }

    /**
     * @return ResponseInterface
     */
    public function render(): ResponseInterface
    {
        return $this->response;
    }
}