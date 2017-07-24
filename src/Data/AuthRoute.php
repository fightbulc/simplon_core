<?php

namespace Simplon\Core\Data;

use Simplon\Core\Interfaces\AuthUserInterface;

/**
 * @package Simplon\Core\Data
 */
class AuthRoute
{
    /**
     * @var string
     */
    private $pattern;
    /**
     * @var array
     */
    private $roles;
    /**
     * @var string|null
     */
    private $fallbackRoute;

    /**
     * @param string $pattern
     * @param array $roles
     * @param string|null $fallbackRoute
     */
    public function __construct(string $pattern, array $roles = [], ?string $fallbackRoute = null)
    {
        $this->pattern = $pattern;
        $this->roles = $roles;
        $this->fallbackRoute = $fallbackRoute;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return bool
     */
    public function hasRoles(): bool
    {
        return empty($this->roles) === false;
    }

    /**
     * @param AuthUserInterface $user
     *
     * @return bool
     */
    public function inRoles(AuthUserInterface $user): bool
    {
        if ($this->hasRoles())
        {
            return $user->getRole() && in_array($user->getRole(), $this->getRoles());
        }

        return true;
    }

    /**
     * @return null|string
     */
    public function getFallbackRoute(): ?string
    {
        return $this->fallbackRoute;
    }
}