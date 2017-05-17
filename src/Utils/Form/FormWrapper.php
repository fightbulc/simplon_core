<?php

namespace Simplon\Core\Utils\Form;

use Simplon\Form\FormFields;
use Simplon\Form\FormValidator;

/**
 * @package Simplon\Core\Utils\Form
 */
class FormWrapper
{
    /**
     * @var FormFields
     */
    private $fields;
    /**
     * @var FormValidator
     */
    protected $validator;

    /**
     * @param FormFields $fields
     * @param array $requestData
     */
    public function __construct(FormFields $fields, array $requestData = [])
    {
        $this->fields = $fields;
        $this->validator = (new FormValidator($requestData))->addFields($fields);
    }

    /**
     * @return FormFields
     */
    public function getFields(): FormFields
    {
        return $this->fields;
    }

    /**
     * @return FormValidator
     */
    public function getValidator(): FormValidator
    {
        return $this->validator;
    }
}