<?php

namespace Simplon\Core\Response;

interface ResponseEncoder
{
    /**
     * @return string
     */
    public function getContentType(): string;

    /**
     * @return string
     */
    public function encodeData(): string;
}