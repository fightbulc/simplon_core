<?php

namespace Simplon\Core\Response;

class JsonResponseEncoder implements ResponseEncoder
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return 'application/json; charset=UTF-8';
    }

    /**
     * @return string
     */
    public function encodeData(): string
    {
        return json_encode($this->data);
    }
}