<?php

namespace Simplon\Core\Interfaces;

/**
 * @package Simplon\Core\Interfaces
 */
interface AuthUserInterface extends DataInterface
{
    /**
     * @return string
     */
    public function getGroup(): string;
}