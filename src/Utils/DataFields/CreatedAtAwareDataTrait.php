<?php

namespace Simplon\Core\Utils\DataFields;

use Simplon\Helper\CastAway;

/**
 * Trait CreatedAtAwareDataTrait
 * @package Simplon\Core\Utils\DataFields
 */
trait CreatedAtAwareDataTrait
{
    /**
     * @var int
     */
    protected $createdAt;

    /**
     * @return static
     */
    public function beforeSave()
    {
        $this->setCreatedAt(time());

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCreatedAt(): ?int
    {
        return CastAway::toInt($this->createdAt);
    }

    /**
     * @param int $createdAt
     *
     * @return static
     */
    public function setCreatedAt(int $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}