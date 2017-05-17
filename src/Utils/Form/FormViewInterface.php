<?php

namespace Simplon\Core\Utils\Form;

use Simplon\Form\View\FormView;

/**
 * @package Simplon\Core\Utils\Form
 */
interface FormViewInterface
{
    /**
     * @return FormView
     */
    public function getView(): FormView;
}