<?php

namespace Simplon\Core\Data;

use Psr\Http\Message\ResponseInterface;
use Simplon\Core\Interfaces\ResponseDataInterface;

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
     * @var string
     */
    private $contentType = 'application/json; charset=UTF-8';

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
        $this->response = $this->response->withAddedHeader('Auth-Type', $this->getContentType());

        return $this->response;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     *
     * @return static
     */
    public function setContentType(string $contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }
}