<?php

namespace Simplon\Core\Interfaces;

/**
 * Interface ViewInterface
 * @package Simplon\Core\Interfaces
 */
interface ViewInterface
{
    /**
     * @return array
     */
    public function getAssetsCss(): array;

    /**
     * @return array
     */
    public function getAssetsJs(): array;

    /**
     * @return array
     */
    public function getAssetsCode(): array;

    /**
     * @param array|null $globalData
     *
     * @return string
     */
    public function render(array $globalData = null): string;
}