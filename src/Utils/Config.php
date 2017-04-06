<?php

namespace Simplon\Core\Utils;

/**
 * Class Config
 * @package Simplon\Core\Utils
 */
class Config
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @param array $config
     *
     * @return Config
     */
    public function addConfig(array $config): self
    {
        $this->config = array_replace_recursive($this->config, $config);

        return $this;
    }

    /**
     * @param array $key
     *
     * @return null|mixed
     */
    public function get(array $key)
    {
        $config = $this->config;

        foreach ($key as $item)
        {
            if (!isset($config[$item]))
            {
                return null;
            }

            $config = $config[$item];
        }

        return $config;
    }
}