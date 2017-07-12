<?php

namespace {namespace}\Views;

use {namespace}\Data\ComponentViewData;
use Simplon\Core\Views\View;
use Simplon\Core\Data\CoreViewData;
use Simplon\Phtml\PhtmlException;

/**
 * @package {namespace}\Views
 */
class {name}PageView extends View
{
    /**
     * @var ComponentViewData
     */
    private $componentViewData;

    /**
     * @param CoreViewData $coreViewData
     * @param ComponentViewData $componentViewData
     */
    public function __construct(CoreViewData $coreViewData, ComponentViewData $componentViewData)
    {
        parent::__construct($coreViewData);
        $this->componentViewData = $componentViewData;
    }

    /**
     * @return string
     */
    protected function getTemplate(): string
    {
        return __DIR__ . '/{name}PageTemplate.phtml';
    }

    /**
     * @return array
     * @throws PhtmlException
     */
    protected function getData(): array
    {
        return $this->componentViewData->toArray(false);
    }
}