<?php

namespace Simplon\Core\Utils\DataFields;

/**
 * Trait StatusIntAwareDataTrait
 * @package Simplon\Core\Utils\DataFields
 */
trait StatusIntAwareDataTrait
{
    /**
     * @var int
     */
    protected $status;

    /**
     * @return null|int
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param null|int $status
     *
     * @return static
     */
    public function setStatus(?int $status)
    {
        $this->status = $status;

        return $this;
    }
}