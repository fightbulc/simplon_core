<?php

namespace Simplon\Core\Interfaces;

/**
 * Interface SessionHandlerInterface
 * @package Interfaces
 */
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