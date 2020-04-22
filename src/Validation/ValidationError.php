<?php

declare(strict_types=1);

namespace JsonMarshler\Validation;

use JsonSerializable;

/**
 * A predicted or actual error found when marshalling JSON input into data classes.
 */
abstract class ValidationError implements JsonSerializable
{
    /**
     * The name of the field which caused the error.
     *
     * @var string
     */
    private string $field;

    /**
     * A human-readable description of the problem.
     *
     * @var string
     */
    private string $problem;

    /**
     * Base constructor for Validation errors.
     *
     * @param string $field   The field the error occurred on.
     * @param string $problem A human-readable description of the error.
     */
    public function __construct(string $field, string $problem)
    {
        $this->field   = $field;
        $this->problem = $problem;
    }

    /**
     * Returns the name of the field which the error occurred for.
     *
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * Returns a human-readable error string describing the validation problem.
     *
     * @return string
     */
    public function getProblem(): string
    {
        return $this->problem;
    }

    /**
     * Serialises this error back into JSON.
     *
     * @inheritDoc
     *
     * @return array<mixed>
     */
    public function jsonSerialize(): array
    {
        return (array) $this;
    }
}
