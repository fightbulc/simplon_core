<?php

namespace Simplon\Core\Interfaces;

/**
 * Interface ViewInterface
 * @package Simplon\Core\Interfaces
 */
interface ViewInterface
{
    /**
     * @param array|null $globalData
     *
     * @return string
     */
    public function render(array $globalData = null): string;
}