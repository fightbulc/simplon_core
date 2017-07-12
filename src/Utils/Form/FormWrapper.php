<?php

namespace Simplon\Core\Utils\Form;

use Simplon\Form\FormError;
use Simplon\Form\FormFields;
use Simplon\Form\FormValidator;

/**
 * @package Simplon\Core\Utils\Form
 */
class FormWrapper
{
    /**
     * @var BaseForm
     */
    private $form;
    /**
     * @var FormValidator
     */
    protected $validator;

    /**
     * @param BaseForm $form
     * @param array $requestData
     *
     * @throws FormError
     */
    public function __construct(BaseForm $form, array $requestData = [])
    {
        $this->form = $form;
        $this->validator = (new FormValidator($requestData))->addFields($this->getFields());
    }

    /**
     * @return FormFields
     * @throws FormError
     */
    public function getFields(): FormFields
    {
        return $this->form->getFields();
    }

    /**
     * @return FormValidator
     */
    public function getValidator(): FormValidator
    {
        return $this->validator;
    }
}