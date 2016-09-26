<?php

namespace Core\Views;

/**
 * Class PartialView
 * @package Core\Views
 */
class PartialView
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $path;
    /**
     * @var array
     */
    protected $data;

    /**
     * @param string $id
     * @param string $path
     * @param array $data
     */
    public function __construct(string $id, string $path, array $data = [])
    {
        $this->id = $id;
        $this->path = $path;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}