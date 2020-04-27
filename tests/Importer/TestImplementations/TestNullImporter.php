<?php

declare(strict_types=1);

namespace JsonMarshler\Importer\TestImplementations;

use JsonMarshler\Importer\RuleBasedImporter;
use JsonMarshler\Validation\Rules\ValidationRule;

/**
 * Test implementation for checking the RuleBasedImporter abstract class.
 *
 * @extends RuleBasedImporter<object>
 */
class TestNullImporter extends RuleBasedImporter
{
    /**
     * Dummy import implementation, returning just the input.
     *
     * @param object $sourceData The data to import.
     *
     * @return object
     */
    public function import(object $sourceData): object
    {
        return $sourceData;
    }

    /**
     * Get the list of rules for checking in tests.
     *
     * @return array<ValidationRule>
     */
    public function getRulesForTest(): array
    {
        return parent::getRules();
    }
}
