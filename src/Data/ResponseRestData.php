<?php

namespace Core\Data;

use Core\Interfaces\ResponseDataInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ResponseRestData
 * @package Core\Data
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
        $this->response = $this->response->withAddedHeader('Content-Type', 'application/json');

        return $this->response;
    }
}