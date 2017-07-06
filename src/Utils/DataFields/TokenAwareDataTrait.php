<?php

namespace Simplon\Core\Utils\DataFields;

/**
 * Trait TokenAwareDataTrait
 * @package Simplon\Core\Utils\DataFields
 */
trait TokenAwareDataTrait
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @return null|string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return static
     */
    public function setToken(string $token)
    {
        $this->token = $token;

        return $this;
    }
}