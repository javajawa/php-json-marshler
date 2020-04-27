<?php

declare(strict_types=1);

namespace JsonMarshler\Validation\Rules;

use JsonMarshler\Validation\ValidationErrors;

/**
 * Extension of the {@link FieldExistsRule} which also permits null values.
 */
class FieldExistsOrNullRule extends FieldExistsRule
{
    /**
     * Run this rule, adding any errors to the {@link ValidationErrors}.
     *
     * @param object           $data   The data to validate against.
     * @param ValidationErrors $errors The output buffer of validation errors.
     *
     * @return void
     */
    public function verify(object $data, ValidationErrors $errors): void
    {
        $fieldName = $this->getFieldName();

        if (!isset($data->{$fieldName})) {
            // Double check for a null (because isset() is also an implicit !is_null).
            $vars = get_object_vars($data);

            if (array_key_exists($fieldName, $vars)) {
                return;
            }
        }

        parent::verify($data, $errors);
    }
}
