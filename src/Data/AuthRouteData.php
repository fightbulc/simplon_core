<?php

namespace Simplon\Core\Data;

use Simplon\Core\Interfaces\AuthUserInterface;

/**
 * @package Simplon\Core\Data
 */
class AuthRouteData
{
    /**
     * @var string
     */
    private $pattern;
    /**
     * @var array
     */
    private $groups;

    /**
     * @param string $pattern
     * @param array $groups
     */
    public function __construct(string $pattern, array $groups = [])
    {
        $this->pattern = $pattern;
        $this->groups = $groups;
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
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @return bool
     */
    public function hasGroups(): bool
    {
        return empty($this->groups) === false;
    }

    /**
     * @param AuthUserInterface $user
     *
     * @return bool
     */
    public function inGroup(AuthUserInterface $user): bool
    {
        return !$this->hasGroups() || in_array($user->getGroup(), $this->groups);
    }
}