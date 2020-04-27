<?php

declare(strict_types=1);

namespace JsonMarshler\Importer;

use JsonMarshler\Importer\TestImplementations\TestNullImporter;
use JsonMarshler\Importer\TestImplementations\TestValidationRule;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class RuleBasedImporterTest
 */
class RuleBasedImporterTest extends TestCase
{
    /**
     * Tests creating and getting rules from an importer created with no rules.
     *
     * @return void
     *
     * @covers \JsonMarshler\Importer\RuleBasedImporter::__construct
     * @covers \JsonMarshler\Importer\RuleBasedImporter::getRules
     */
    public function testConstruct(): void
    {
        $rule     = new TestValidationRule();
        $importer = new TestNullImporter($rule);

        $rules = $importer->getRulesForTest();
        self::assertIsArray($rules);
        self::assertCount(1, $rules);
        self::assertSame($rule, $rules[0]);
    }

    /**
     * Check that calling validate calls the installed rules.
     *
     * @return void
     *
     * @covers \JsonMarshler\Importer\RuleBasedImporter::validate
     *
     * @uses \JsonMarshler\Importer\RuleBasedImporter::__construct
     * @uses \JsonMarshler\Validation\Rules\ValidationRule
     * @uses \JsonMarshler\Validation\ValidationErrors
     */
    public function testValidate(): void
    {
        $validator = new TestValidationRule();

        $importer = new TestNullImporter($validator);

        $errors = $importer->validate(new stdClass());

        self::assertFalse($errors->hasErrors());
        self::assertTrue($validator->wasCalled(), 'Validator was not called');
    }

    /**
     * Test that a validator with no rules is functional.
     *
     * @return void
     *
     * @covers \JsonMarshler\Importer\RuleBasedImporter::__construct
     * @covers \JsonMarshler\Importer\RuleBasedImporter::validate
     *
     * @uses \JsonMarshler\Validation\ValidationErrors
     */
    public function testValidateEmpty(): void
    {
        $importer = new TestNullImporter();

        $errors = $importer->validate(new stdClass());

        self::assertFalse($errors->hasErrors());
    }

    /**
     * Check that the addRule function works and that rules are returned in
     * a consistent order.
     *
     * @return void
     *
     * @covers \JsonMarshler\Importer\RuleBasedImporter::__construct
     * @covers \JsonMarshler\Importer\RuleBasedImporter::addRule
     * @covers \JsonMarshler\Importer\RuleBasedImporter::getRules
     */
    public function testAddRule(): void
    {
        $importer = new TestNullImporter();
        $rule1    = new TestValidationRule();
        $rule2    = new TestValidationRule();

        $rules = $importer->getRulesForTest();
        self::assertIsArray($rules);
        self::assertCount(0, $rules);

        $importer->addRule($rule1);

        $rules = $importer->getRulesForTest();
        self::assertIsArray($rules);
        self::assertCount(1, $rules);
        self::assertSame($rule1, $rules[0]);

        $importer->addRule($rule2);

        $rules = $importer->getRulesForTest();
        self::assertIsArray($rules);
        self::assertCount(2, $rules);
        self::assertSame($rule1, $rules[0]);
        self::assertNotSame($rule2, $rules[0]);
        self::assertSame($rule2, $rules[1]);
        self::assertNotSame($rule1, $rules[1]);
    }
}
