<?php

namespace Simplon\Core\Middleware\Auth;

use Simplon\Core\Interfaces\AuthUserInterface;
use Simplon\Helper\Data\Data;

/**
 * @package Simplon\Core\Middleware\Auth
 */
abstract class AuthUser extends Data implements AuthUserInterface
{
    /**
     * @return null|string
     */
    public function getGroup(): ?string
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