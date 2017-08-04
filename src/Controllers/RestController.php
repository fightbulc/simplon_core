<?php

namespace Simplon\Core\Controllers;

use Psr\Http\Message\ResponseInterface;
use Simplon\Core\Data\ResponseRestData;

/**
 * Class RestController
 * @package Simplon\Core\Controllers
 */
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

        $response->getBody()->write(json_encode($data));

        return new ResponseRestData($response);
    }

    /**
     * @return array|null|object
     */
    public function getRequestBody()
    {
        return json_decode($this->getRequest()->getBody()->getContents(), true);
    }
}