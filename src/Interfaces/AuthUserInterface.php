<?php

namespace Simplon\Core\Interfaces;

use Simplon\Helper\Interfaces\DataInterface;

/**
 * @package Simplon\Core\Interfaces
 */
interface AuthUserInterface extends DataInterface
{
    /**
     * @return null|string
     */
    public function getRole(): ?string;

    /**
     * @return bool
     */
    public function isSuperUser(): bool;
}