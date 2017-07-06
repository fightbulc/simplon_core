<?php

namespace Simplon\Core\Utils\DataFields;

use Simplon\Helper\CastAway;

/**
 * Trait IdAwareDataTrait
 * @package Simplon\Core\Utils\DataFields
 */
trait IdAwareDataTrait
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return CastAway::toInt($this->id);
    }

    /**
     * @param int $id
     *
     * @return static
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }
}