<?php

namespace Simplon\Core\Utils\DataFields;

/**
 * Trait StatusStringAwareDataTrait
 * @package Simplon\Core\Utils\DataFields
 */
trait StatusStringAwareDataTrait
{
    /**
     * @var string|null
     */
    protected $status;

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     *
     * @return static
     */
    public function setStatus(?string $status)
    {
        $this->status = $status;

        return $this;
    }
}