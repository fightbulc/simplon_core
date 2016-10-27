<?php

namespace Simplon\Core\Interfaces;

/**
 * Interface DataInterface
 * @package Simplon\Core\Interfaces
 */
interface DataInterface
{
    /**
     * @param array $data
     *
     * @return static
     */
    public function fromArray(array $data);

    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @param bool $snakeCase
     *
     * @return string
     */
    public function toJson(bool $snakeCase = true): string;

    /**
     * @param string $json
     *
     * @return static
     */
    public function fromJson(string $json);
}