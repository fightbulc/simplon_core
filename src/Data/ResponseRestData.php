<?php

namespace Simplon\Core\Data;

use Psr\Http\Message\ResponseInterface;
use Simplon\Core\Interfaces\ResponseDataInterface;
use Simplon\Core\Response\ResponseEncoder;

class ResponseRestData implements ResponseDataInterface
{
    /**
     * @var ResponseInterface
     */
    private $response;
    /**
     * @var ResponseEncoder
     */
    private $encoder;

    /**
     * @param ResponseEncoder $encoder
     * @param ResponseInterface $response
     */
    public function __construct(ResponseEncoder $encoder, ResponseInterface $response)
    {
        $this->response = $response;
        $this->encoder = $encoder;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return ResponseInterface
     */
    public function render(): ResponseInterface
    {
        $this->response = $this->response->withAddedHeader('Content-Type', $this->encoder->getContentType());
        $this->response->getBody()->write($this->encoder->encodeData());

        return $this->response;
    }
}