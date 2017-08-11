<?php

namespace Simplon\Core\Utils\DataFields;

use Simplon\Helper\CastAway;

trait TimeAwareDataTrait
{
    /**
     * @var int
     */
    protected $createdAt;
    /**
     * @var int
     */
    protected $updatedAt;

    /**
     * @return static
     */
    public function beforeSave()
    {
        $this->setCreatedAt(time())->setUpdatedAt(time());

        return $this;
    }

    /**
     * @return static
     */
    public function beforeUpdate()
    {
        $this->setUpdatedAt(time());

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

    /**
     * @return int|null
     */
    public function getUpdatedAt(): ?int
    {
        return CastAway::toInt($this->updatedAt);
    }

    /**
     * @param int $updatedAt
     *
     * @return static
     */
    public function setUpdatedAt(int $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}