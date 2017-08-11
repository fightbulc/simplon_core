<?php

namespace Simplon\Core\Interfaces;

use Simplon\Helper\Interfaces\DataInterface;

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