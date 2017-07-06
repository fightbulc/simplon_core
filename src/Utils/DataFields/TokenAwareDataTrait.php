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
    protected $pubToken;

    /**
     * @return string
     */
    public function getPubToken(): string
    {
        return $this->pubToken;
    }

    /**
     * @param string $pubToken
     *
     * @return static
     */
    public function setPubToken(string $pubToken)
    {
        $this->pubToken = $pubToken;

        return $this;
    }
}