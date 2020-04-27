<?php

declare(strict_types=1);

namespace JsonMarshler\Importer\TestImplementations;

use JsonMarshler\Validation\Rules\ValidationRule;
use JsonMarshler\Validation\ValidationErrors;

/**
 * A test implementation of the ValidationRule interface.
 *
 * It never provides an error, and maintain a count of how many times it has
 * been used.
 */
class TestValidationRule extends ValidationRule
{
    /**
     * Whether this validator was called.
     *
     * @var bool
     */
    private bool $called = false;

    /**
     * Whether this validator was called.
     *
     * @var int
     */
    private int $callCount = 0;

    /**
     * Run this rule, recording the fact it has been called.
     * The {@link ValidationErrors} is not altered.
     *
     * @param object           $data   The data to validate against.
     * @param ValidationErrors $errors The output buffer of validation errors.
     *
     * @return void
     */
    public function verify(object $data, ValidationErrors $errors): void
    {
        $this->called     = true;
        $this->callCount += 1;

        // This line has no side effects, but prevents the linters from
        // considered them as unused parameters.
        [$data, $errors];
    }

    /**
     * Whether verify() has been called.
     *
     * @return bool
     */
    public function wasCalled(): bool
    {
        return $this->called;
    }

    /**
     * How many times verify() has been called.
     *
     * @return int
     */
    public function getCallCount(): int
    {
        return $this->callCount;
    }
}
