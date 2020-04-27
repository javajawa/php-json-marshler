<?php

declare(strict_types=1);

namespace JsonMarshler\Validation\Rules;

use JsonMarshler\Validation\ValidationErrors;

/**
 * Abstract base class for a validation rules
 */
abstract class ValidationRule
{
    /**
     * Run this rule, adding any errors to the {@link ValidationErrors}.
     *
     * @param object           $data   The data to validate against.
     * @param ValidationErrors $errors The output buffer of validation errors.
     *
     * @return void
     */
    abstract public function verify(object $data, ValidationErrors $errors): void;
}
