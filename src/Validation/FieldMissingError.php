<?php

declare(strict_types=1);

namespace JsonMarshler\Validation;

/**
 * Represents an issue with a JSON input when a required field is missing.
 */
class FieldMissingError extends ValidationError
{
    /**
     * ValidationError constructor.
     *
     * @param string $field The field that is missing.
     */
    public function __construct(string $field)
    {
        parent::__construct($field, 'Field not present in object');
    }
}
