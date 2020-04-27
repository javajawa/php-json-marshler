<?php

declare(strict_types=1);

namespace JsonMarshler\Validation\Rules;

use JsonMarshler\Validation\Error\UnexpectedFieldError;
use JsonMarshler\Validation\ValidationErrors;
use PHPUnit\Framework\TestCase;

/**
 * Test for NoOtherFields Rule
 */
class NoOtherFieldsTest extends TestCase
{
    /**
     * The JSON string to use for the test dataset.
     */
    private const JSON_DATA = '{
        "id":1, 
        "name":"Benedict",
        "active": true,
        "purpose": null
    }';

    /**
     * The JSON data to test against.
     *
     * @var object
     */
    private object $data;

    /**
     * Tests the error reporting of the NoOtherFields rule.
     *
     * @param array<string> $acceptedFields Fields to give to the rule's whitelist.
     * @param array<string> $errorFields    Fields that should be in the errors.
     *
     * @return void
     *
     * @dataProvider fieldCombinations
     *
     * @covers \JsonMarshler\Validation\Rules\NoOtherFields::__construct
     * @covers \JsonMarshler\Validation\Rules\NoOtherFields::verify
     *
     * @uses \JsonMarshler\Validation\ValidationErrors
     * @uses \JsonMarshler\Validation\Error\ValidationError
     * @uses \JsonMarshler\Validation\Error\UnexpectedFieldError
     */
    public function test(array $acceptedFields, array $errorFields): void
    {
        $rule   = new NoOtherFields(...$acceptedFields);
        $errors = new ValidationErrors();

        $rule->verify($this->data, $errors);

        self::assertCount(count($errorFields), $errors);

        $erroredFields = [];

        foreach ($errors as $error) {
            self::assertInstanceOf(UnexpectedFieldError::class, $error);
            $erroredFields[] = $error->getField();
        }

        foreach ($errorFields as $field) {
            self::assertContains($field, $erroredFields);
        }
    }

    /**
     * Combinations of fields to test.
     *
     * @return array<string, array{array<int, string>, array<int, string>}>
     */
    public function fieldCombinations(): array
    {
        return [
            'Empty Whitelist'  => [ [], ['id', 'name', 'active', 'purpose', ] ],
            'Only ID and Name' => [ ['id', 'name', ], ['active', 'purpose', ] ],
        ];
    }

    /**
     * Loads the base JSON in for the test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->data = json_decode(self::JSON_DATA);
    }
}
