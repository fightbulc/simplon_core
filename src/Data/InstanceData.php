<?php

namespace Simplon\Core\Data;

/**
 * @package Simplon\Core\Data
 */
class InstanceData
{
    /**
     * @var string
     */
    private $className;
    /**
     * @var string
     */
    private $cacheName;
    /**
     * @var array
     */
    private $params = [];
    /**
     * @var \Closure
     */
    private $paramsBuilder;
    /**
     * @var \Closure
     */
    private $afterCallback;

    /**
     * @param string $className
     *
     * @return InstanceData
     */
    public static function create(string $className): InstanceData
    {
        return new InstanceData($className);
    }

    /**
     * @param string $className
     */
    public function __construct(string $className)
    {
        $this->className = $className;
        $this->cacheName = $className;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     *
     * @return InstanceData
     */
    public function setClassName(string $className): InstanceData
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @return string
     */
    public function getCacheName(): string
    {
        return $this->cacheName;
    }

    /**
     * @param string $cacheName
     *
     * @return InstanceData
     */
    public function setCacheName(string $cacheName): InstanceData
    {
        $this->cacheName = $cacheName;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams(): ?array
    {
        return $this->params;
    }

    /**
     * @param mixed $param
     *
     * @return InstanceData
     */
    public function addParam($param): InstanceData
    {
        $this->params[] = $param;

        return $this;
    }

    /**
     * @param array $params
     *
     * @return InstanceData
     */
    public function setParams(array $params): InstanceData
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return \Closure
     */
    public function getParamsBuilder(): \Closure
    {
        return $this->paramsBuilder;
    }

    /**
     * @return bool
     */
    public function hasParamsBuilder(): bool
    {
        return is_\Closure($this->paramsBuilder);
    }

    /**
     * @param \Closure $paramsBuilder
     *
     * @return InstanceData
     */
    public function setParamsBuilder(\Closure $paramsBuilder): InstanceData
    {
        $this->paramsBuilder = $paramsBuilder;

        return $this;
    }

    /**
     * @return \Closure
     */
    public function getAfterCallback(): \Closure
    {
        return $this->afterCallback;
    }

    /**
     * @return bool
     */
    public function hasAfterCallback(): bool
    {
        return is_\Closure($this->afterCallback);
    }

    /**
     * @param \Closure $afterCallback
     *
     * @return InstanceData
     */
    public function setAfterCallback(\Closure $afterCallback): InstanceData
    {
        $this->afterCallback = $afterCallback;

        return $this;
    }
}