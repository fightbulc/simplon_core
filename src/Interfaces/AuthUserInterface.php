<?php

namespace Simplon\Core\Interfaces;

/**
 * @package Simplon\Core\Interfaces
 */
interface AuthUserInterface
{
    /**
     * @return string
     */
    public function getGroup(): string;
}