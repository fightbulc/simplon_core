<?php

namespace Core\Interfaces;

/**
 * Interface DataInterface
 * @package Core\Interfaces
 */
interface DataInterface
{
    /**
     * @param array $data
     *
     * @return DataInterface
     */
    public function fromArray(array $data): self;

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
     * @return DataInterface
     */
    public function fromJson(string $json): DataInterface;
}