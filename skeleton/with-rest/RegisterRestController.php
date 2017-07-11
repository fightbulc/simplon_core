<?php

namespace {namespace}\Controllers;

use Simplon\Core\Data\ResponseRestData;

/**
 * @package {namespace}\Controllers
 */
class FooRestController extends BaseRestController
{
    /**
     * @param array $params
     *
     * @return ResponseRestData
     */
    public function __invoke(array $params): ResponseRestData
    {
        return $this->respond([
            'hello' => 'world',
        ]);
    }
}