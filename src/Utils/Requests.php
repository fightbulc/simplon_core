<?php

namespace Simplon\Core\Utils;

use Simplon\Http\HttpInterface;

abstract class Requests
{
    /**
     * @return HttpInterface
     */
    abstract protected function getHttp(): HttpInterface;
}