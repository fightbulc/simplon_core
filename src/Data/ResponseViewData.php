<?php

namespace Core\Data;

use Core\Interfaces\ResponseDataInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ResponseViewData
 * @package Core\Data
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