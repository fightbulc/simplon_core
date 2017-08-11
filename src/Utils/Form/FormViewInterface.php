<?php

namespace Simplon\Core\Utils\Form;

use Simplon\Form\View\FormView;

interface FormViewInterface
{
    /**
     * @return FormView
     */
    public function getView(): FormView;
}