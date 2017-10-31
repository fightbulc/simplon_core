<?php

namespace Simplon\Core\Controllers;

use Psr\Http\Message\ResponseInterface;
use Simplon\Core\Data\ResponseRestData;
use Simplon\Core\Response\JsonResponseEncoder;
use Simplon\Core\Response\ResponseEncoder;

abstract class RestController extends Controller
{
    /**
     * @param array $params
     *
     * @return ResponseRestData
     */
    abstract public function __invoke(array $params): ResponseRestData;

    /**
     * @param array $data
     * @param null|ResponseInterface $response
     *
     * @return ResponseRestData
     */
    public function respond(array $data, ?ResponseInterface $response = null): ResponseRestData
    {
        if (!$response)
        {
            $response = $this->getResponse();
        }

        return new ResponseRestData($this->getEncoder($data), $response);
    }

    /**
     * @param array $data
     *
     * @return ResponseEncoder
     */
    protected function getEncoder(array $data): ResponseEncoder
    {
        return new JsonResponseEncoder($data);
    }

    /**
     * @return array|null|object
     */
    protected function getRequestBody()
    {
        return json_decode($this->getRequest()->getBody()->getContents(), true);
    }
}