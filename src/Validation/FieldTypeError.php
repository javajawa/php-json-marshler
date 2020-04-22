<?php

declare(strict_types=1);

namespace JsonMarshler\Validation;

/**
 * Validation error caused by a field in the JSON not having the correct
 * type to be imported.
 */
class FieldTypeError extends ValidationError
{
    /**
     * The expected JSON type in this field.
     *
     * @var string
     */
    private string $expected;

    /**
     * The JSON type found in this field.
     *
     * @var string
     */
    private string $found;

    /**
     * FieldTypeError constructor.
     *
     * @param string $field    The name of the field.
     * @param string $expected The expected JSON type for the field.
     * @param string $found    The JSON type found in the field.
     */
    public function __construct(string $field, string $expected, string $found)
    {
        parent::__construct($field, 'Expected type ' . $expected . ', found ' . $found);

        $this->expected = $expected;
        $this->found    = $found;
    }

    /**
     * Get the expected JSON type in this field.
     *
     * @return string
     */
    public function getExpectedType(): string
    {
        return $this->expected;
    }

    /**
     * Get JSON type found in this field.
     *
     * @return string
     */
    public function getFoundType(): string
    {
        return $this->found;
    }
}
