<?php

namespace Core\Utils;

/**
 * Class Config
 * @package Core\Utils
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
    public function __construct(array $config)
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
     * @return mixed
     * @throws \Exception
     */
    public function get(array $key)
    {
        $config = $this->config;

        foreach ($key as $item)
        {
            if (!isset($config[$item]))
            {
                throw new \Exception('Following key cannot be find within given config: ' . join('=>', $key));
            }

            $config = $config[$item];
        }

        return $config;
    }
}