<?php

namespace Core\Controllers;

use Core\Data\ResponseRestData;

/**
 * Class RestController
 * @package Core\Controllers
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
     *
     * @return ResponseRestData
     */
    public function respond(array $data): ResponseRestData
    {
        $this->getResponse()->getBody()->write(json_encode($data));

        return new ResponseRestData($this->getResponse());
    }
}