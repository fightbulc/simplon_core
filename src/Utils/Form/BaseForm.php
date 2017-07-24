<?php

namespace Simplon\Core\Utils\Form;

use Simplon\Form\Data\FormField;
use Simplon\Form\FormError;
use Simplon\Form\FormFields;

/**
 * @package Simplon\Core\Utils\Form
 */
abstract class BaseForm
{
    /**
     * @var FormFields
     */
    protected $formFields;

    /**
     * @return FormFields
     * @throws FormError
     */
    public function getFields(): FormFields
    {
        if (!$this->formFields)
        {
            $this->formFields = new FormFields();

            foreach ($this->buildFields() as $field)
            {
                $this->formFields->add($field);
            }
        }

        return $this->formFields;
    }

    /**
     * @return FormField[]
     */
    abstract protected function buildFields(): array;
}