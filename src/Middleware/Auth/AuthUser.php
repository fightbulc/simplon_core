<?php

namespace Simplon\Core\Middleware\Auth;

use Simplon\Core\Interfaces\AuthUserInterface;
use Simplon\Helper\Data\Data;

abstract class AuthUser extends Data implements AuthUserInterface
{
    /**
     * @return null|string
     */
    public function getRole(): ?string
    {
        return null;
    }

    /**
     * @return bool
     */
    public function isSuperUser(): bool
    {
        return false;
    }
}