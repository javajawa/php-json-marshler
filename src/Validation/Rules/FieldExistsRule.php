<?php

declare(strict_types=1);

namespace JsonMarshler\Validation\Rules;

use JsonMarshler\Json\JsonType;
use JsonMarshler\Validation\Error\FieldMissingError;
use JsonMarshler\Validation\Error\FieldTypeError;
use JsonMarshler\Validation\ValidationErrors;

/**
 * Basic rule for checking the existence of a field and optionally its type.
 */
class FieldExistsRule extends ValidationRule
{
    /**
     * The field to check for.
     *
     * @var string
     */
    private string $fieldName;

    /**
     * The type of the field, one of the constants from {@link JsonType}.
     *
     * @var string
     */
    private string $type;

    /**
     * Constructs a field existence and type check validation rule.
     *
     * @param string $fieldName The name of the field to check for.
     * @param string $type      The type the field must be.
     */
    public function __construct(string $fieldName, string $type = JsonType::JSON_ANY)
    {
        $this->fieldName = $fieldName;
        $this->type      = $type;
    }

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
        if (!isset($data->{$this->fieldName})) {
            $errors->add(new FieldMissingError($this->fieldName));

            return;
        }

        if ($this->type === JsonType::JSON_ANY) {
            return;
        }

        $value = $data->{$this->fieldName};

        $detectedType = gettype($data->{$this->fieldName});

        if ($this->type === $detectedType) {
            return;
        }

        if ($detectedType === JsonType::JSON_STRING) {
            if ($this->type === JsonType::JSON_INT || $this->type === JsonType::JSON_FLOAT) {
                if (is_numeric($value)) {
                    return;
                }
            }
        }

        $errors->add(new FieldTypeError($this->fieldName, $this->type, $detectedType));
    }

    /**
     * Gets the field name for the rule, for use in sub-classes.
     *
     * @return string
     */
    protected function getFieldName(): string
    {
        return $this->fieldName;
    }
}
