<?php

namespace Simplon\Core\Interfaces;

interface SessionHandlerInterface
{
    /**
     * @return string
     */
    public function getSavePath(): string;

    /**
     * @return string
     */
    public function getSaveHandler(): string;
}