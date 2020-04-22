<?php declare(strict_types = 1);

namespace JsonMarshler\Validation;

class FieldTypeError extends ValidationError
{
    private string $expected;
    private string $found;

    /**
     * FieldTypeError constructor.
     *
     * @param string $field
     * @param string $expected
     * @param string $found
     */
    public function __construct(string $field, string $expected, string $found)
    {
        parent::__construct($field, 'Expected type '. $expected . ', found ' . $found);

        $this->expected = $expected;
        $this->found    = $found;
    }
}
