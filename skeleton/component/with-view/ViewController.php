<?php

namespace {namespace}\Controllers;

use {namespace}\Views\Foo\FooView;
use Simplon\Core\Data\ResponseViewData;

/**
 * @package {namespace}\Controllers
 */
class {name}ViewController extends BaseViewController
{
    /**
     * @param array $params
     *
     * @return ResponseViewData
     */
    public function __invoke(array $params): ResponseViewData
    {
        return $this->respond(
            $this->buildPage(
                new FooView($this->getCoreViewData()),
                $this->getComponentViewData(),
                $this->getGlobalViewData()
            )
        );
    }
}