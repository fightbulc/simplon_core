<?php

namespace Simplon\Core\Utils\Form;

use Simplon\Form\Data\FormField;
use Simplon\Form\FormError;
use Simplon\Form\FormFields;

/**
 * @package Simplon\Core\Utils\Form
 */
abstract class BaseFormFields
{
    /**
     * @var FormFields
     */
    protected $formFields;

    /**
     * @return FormFields
     * @throws FormError
     */
    public function getFormFields(): FormFields
    {
        if (!$this->formFields)
        {
            $this->formFields = new FormFields();

            foreach ($this->getFields() as $field)
            {
                $this->formFields->add($field);
            }
        }

        return $this->formFields;
    }

    /**
     * @return FormField[]
     */
    abstract protected function getFields(): array;
}