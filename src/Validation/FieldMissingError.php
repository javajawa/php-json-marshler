<?php declare(strict_types = 1);

namespace JsonMarshler\Validation;

class FieldMissingError extends ValidationError
{
    /**
     * ValidationError constructor.
     *
     * @param string $field
     */
    public function __construct(string $field)
    {
        parent::__construct($field, 'Field not present in object');
    }
}
