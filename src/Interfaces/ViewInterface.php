<?php

namespace Core\Interfaces;

/**
 * Interface ViewInterface
 * @package Core\Interfaces
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