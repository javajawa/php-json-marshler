<?php

declare(strict_types=1);

namespace JsonMarshler\Importer;

use JsonMarshler\Validation\Rules\ValidationRule;
use JsonMarshler\Validation\ValidationErrors;

/**
 * Abstract importer which stores and uses a series of {@link ValidationRule}s
 * to check that an object is importable.
 *
 * @template T of object
 *
 * @implements ObjectImporter<T>
 */
abstract class RuleBasedImporter implements ObjectImporter
{
    /**
     * The list of rules
     *
     * @var array<ValidationRule>
     */
    private array $rules;

    /**
     * Sets up the validator with an initial set of rules.
     *
     * @param ValidationRule ...$rules The initial set of rules.
     */
    public function __construct(ValidationRule ...$rules)
    {
        $this->rules = $rules;
    }

    /**
     * Appends a rule to the list of validation rules in this class.
     *
     * @param ValidationRule $rule The rule to add.
     *
     * @return $this
     */
    public function addRule(ValidationRule $rule): self
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * Validates that a given JSON blob meets all of the validation rules
     * configured in this imports.
     *
     * A {@link ValidationErrors} collection is always returned, and
     * the calling code can check for errors with
     * {@link ValidationErrors::hasErrors()}.
     *
     * If the returned value contains no errors, then calling
     * {@link ObjectImporter::import()} with the same arguments *must*
     * always result in an valid imported object being returned.
     *
     * @param object $sourceData The JSON blob to validate.
     *
     * @return ValidationErrors
     */
    public function validate(object $sourceData): ValidationErrors
    {
        $errors = new ValidationErrors();

        foreach ($this->rules as $rule) {
            $rule->verify($sourceData, $errors);
        }

        return $errors;
    }

    /**
     * Internal function for getting the current list of rules.
     *
     * @return array<ValidationRule>
     */
    protected function getRules(): array
    {
        return $this->rules;
    }
}
