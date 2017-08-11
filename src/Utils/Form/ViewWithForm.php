<?php

namespace Simplon\Core\Utils\Form;

use Simplon\Core\Data\CoreViewData;
use Simplon\Core\Views\View;

abstract class ViewWithForm extends View
{
    /**
     * @var FormViewInterface
     */
    protected $formView;

    /**
     * @param CoreViewData $coreViewData
     * @param FormViewInterface $formView
     */
    public function __construct(CoreViewData $coreViewData, FormViewInterface $formView)
    {
        parent::__construct($coreViewData);
        $this->addFormAssets($formView->getView(), $this->getIgnoreFormAssets());
        $this->formView = $formView;
    }

    /**
     * @return array
     */
    protected function getData(): array
    {
        return [
            'formView' => $this->formView->getView(),
        ];
    }

    /**
     * @return array
     */
    protected function getIgnoreFormAssets(): array
    {
        return ['jquery', 'semantic'];
    }
}