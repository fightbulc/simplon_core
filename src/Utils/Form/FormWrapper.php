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
     * @param array $initialData
     *
     * @throws FormError
     */
    public function __construct(BaseForm $form, array $requestData = [], array $initialData = [])
    {
        $this->form = $form;
        $this->validator = (new FormValidator($requestData))->addFields($this->getFields());

        if (!$this->validator->hasBeenSubmitted())
        {
            $this->getFields()->applyInitialData($initialData);
        }
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
     * @param string $fieldId
     *
     * @return mixed|null
     * @throws FormError
     */
    public function getVal(string $fieldId)
    {
        $value = $this->getFields()->get($fieldId)->getValue();

        if ($value !== '')
        {
            return $value;
        }

        return null;
    }

    /**
     * @return FormValidator
     */
    public function getValidator(): FormValidator
    {
        return $this->validator;
    }
}