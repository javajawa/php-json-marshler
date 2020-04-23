<?php

declare(strict_types=1);

namespace JsonMarshler\Validation\Rules;

use JsonMarshler\Validation\ValidationErrors;
use stdClass;

/**
 * Abstract base class for a validation rules
 */
abstract class ValidationRule
{
    /**
     * Run this rule, adding any errors to the {@link ValidationErrors}
     *
     * @param stdClass $data
     * @param ValidationErrors $errors
     */
    abstract public function verify(stdClass $data, ValidationErrors $errors): void;
}
