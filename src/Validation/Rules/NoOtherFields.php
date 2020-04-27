<?php

declare(strict_types=1);

namespace JsonMarshler\Validation\Rules;

use JsonMarshler\Validation\Error\UnexpectedFieldError;
use JsonMarshler\Validation\ValidationErrors;

/**
 * Validation rule which adds an error for each field in a JSON object which
 * is not part of a whitelist of fields.
 */
class NoOtherFields extends ValidationRule
{
    /**
     * The list of fields that are acceptable in this object.
     *
     * @var array<string>
     */
    private array $acceptableFieldList;

    /**
     * NoOtherFields constructor.
     *
     * @param string ...$acceptableFieldList Names of fields which are expected.
     */
    public function __construct(string ...$acceptableFieldList)
    {
        $this->acceptableFieldList = $acceptableFieldList;
    }

    /**
     * Run this rule, adding an error to the {@link ValidationErrors} for each
     * field in the data object
     *
     * @param object           $data   The data to validate against.
     * @param ValidationErrors $errors The output buffer of validation errors.
     *
     * @return void
     */
    public function verify(object $data, ValidationErrors $errors): void
    {
        $fields = array_keys(get_object_vars($data));

        $unexpectedFields = array_diff($fields, $this->acceptableFieldList);

        foreach ($unexpectedFields as $field) {
            $errors->add(new UnexpectedFieldError($field));
        }
    }
}
