<?php

namespace Core\Views;

/**
 * Class PageView
 * @package Core\Views
 */
class PageView
{
    /**
     * @var string
     */
    protected $pagePath;
    /**
     * @var PartialView[]
     */
    protected $partials = [];
    /**
     * @var array
     */
    protected $globalData = [];

    /**
     * @return string
     */
    public function getPagePath(): string
    {
        return $this->pagePath;
    }

    /**
     * @param string $pagePath
     *
     * @return PageView
     */
    public function setPagePath(string $pagePath): self
    {
        $this->pagePath = $pagePath;

        return $this;
    }

    /**
     * @return PartialView[]
     */
    public function getPartials(): array
    {
        return $this->partials;
    }

    /**
     * @param PartialView $partialView
     *
     * @return PageView
     */
    public function addPartial(PartialView $partialView): self
    {
        $this->partials[] = $partialView;

        return $this;
    }

    /**
     * @return array
     */
    public function getGlobalData(): array
    {
        return $this->globalData;
    }

    /**
     * @param array $data
     *
     * @return PageView
     * @throws \Exception
     */
    public function addGlobalData(array $data): self
    {
        foreach ($data as $key => $val)
        {
            if (isset($this->globalData[$key]))
            {
                throw new \Exception('Global data with key "' . $key . '" exists already');
            }

            $this->globalData[$key] = $val;
        }

        return $this;
    }
}