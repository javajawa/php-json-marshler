<?php

namespace JsonMarshler\Validation\Rules;

use JsonMarshler\Validation\Error\UnexpectedFieldError;
use JsonMarshler\Validation\ValidationErrors;
use stdClass;

class NoOtherFields extends ValidationRule
{
    /**
     * The list of fields that are acceptable in this object.
     *
     * @var array<string>
     */
    private array $acceptableFieldList;


    public function verify(stdClass $data, ValidationErrors $errors): void
    {
        $fields = array_keys(get_object_vars($data));

        $unexpectedFields = array_diff($fields, $this->acceptableFieldList);

        foreach ($unexpectedFields as $field) {
            $errors->add(new UnexpectedFieldError($field));
        }
    }


}
