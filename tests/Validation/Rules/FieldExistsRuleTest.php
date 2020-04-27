<?php

declare(strict_types=1);

namespace JsonMarshler\Validation\Rules;

use JsonMarshler\Json\JsonType;
use JsonMarshler\Validation\Error\FieldMissingError;
use JsonMarshler\Validation\Error\FieldTypeError;
use JsonMarshler\Validation\ValidationErrors;
use PHPUnit\Framework\TestCase;

/**
 * Tests for FieldExistsRule
 */
class FieldExistsRuleTest extends TestCase
{
    /**
     * The JSON string to use for the test dataset.
     */
    private const JSON_DATA = '{
        "id":1, 
        "name":"Benedict",
        "active": true,
        "age": "28",
        "purpose": null,
        "flot": "38.4"
    }';

    /**
     * The JSON data to test against.
     *
     * @var object
     */
    private object $data;

    /**
     * Creates a {@link FieldExistsRule} rule with the given specification
     * and tests it against {@link FieldExistsRuleTest::JSON_DATA}, checking
     * if the Type is correct.
     *
     * @param string $field  The field to check for.
     * @param bool   $result The expected result of the test.
     *
     * @return void
     *
     * @dataProvider casesForExistenceTest
     *
     * @covers \JsonMarshler\Validation\Rules\FieldExistsRule::__construct
     * @covers \JsonMarshler\Validation\Rules\FieldExistsRule::verify
     *
     * @uses \JsonMarshler\Validation\ValidationErrors
     * @uses \JsonMarshler\Validation\Error\FieldMissingError
     * @uses \JsonMarshler\Validation\Error\ValidationError
     */
    public function testVerifyFieldExists(string $field, bool $result): void
    {
        $errors = new ValidationErrors();

        $rule = new FieldExistsRule($field);
        $rule->verify($this->data, $errors);

        if ($result) {
            self::assertFalse($errors->hasErrors());

            return;
        }

        self::assertTrue($errors->hasErrors());
        self::assertCount(1, $errors);
        self::assertInstanceOf(FieldMissingError::class, $errors->current());
    }

    /**
     * Test cases for the type testing.
     *
     * @return array<string, array{string, bool}>
     */
    public function casesForExistenceTest(): array
    {
        return [
            'id (exists)'                   => ['id', true],
            'foo (non-existent)'            => ['foo', false],
            'ID (non-existent: wrong case)' => ['ID', false],
            'purpose (null)'                => ['purpose', false],
        ];
    }

    /**
     * Creates a {@link FieldExistsRule} rule with the given specification
     * and tests it against {@link FieldExistsRuleTest::JSON_DATA}, checking
     * if the Type is correct.
     *
     * @param string $field  The field to check for.
     * @param string $type   The required type from {@link JsonType}.
     * @param bool   $result The expected result of the test.
     *
     * @return void
     *
     * @dataProvider casesForTypeTest
     *
     * @covers \JsonMarshler\Validation\Rules\FieldExistsRule
     *
     * @uses \JsonMarshler\Validation\ValidationErrors
     * @uses \JsonMarshler\Validation\Error\FieldTypeError
     * @uses \JsonMarshler\Validation\Error\ValidationError
     */
    public function testVerifyFieldType(string $field, string $type, bool $result): void
    {
        $rule   = new FieldExistsRule($field, $type);
        $errors = new ValidationErrors();

        $rule->verify($this->data, $errors);

        if ($result) {
            self::assertFalse($errors->hasErrors());

            return;
        }

        self::assertTrue($errors->hasErrors());
        self::assertCount(1, $errors);
        self::assertInstanceOf(FieldTypeError::class, $errors->current());
    }

    /**
     * Test cases for the type testing.
     *
     * @return array<string, array{string, string, bool}>
     */
    public function casesForTypeTest(): array
    {
        return [
            'ID is any'             => ['id',      JsonType::JSON_ANY,    true  ],
            'ID is int'             => ['id',      JsonType::JSON_INT,    true  ],
            'ID is not float'       => ['id',      JsonType::JSON_FLOAT,  false ],
            'ID is not string'      => ['id',      JsonType::JSON_STRING, false ],
            'ID is not bool'        => ['id',      JsonType::JSON_BOOL,   false ],
            'ID is not array'       => ['id',      JsonType::JSON_ARRAY,  false ],
            'ID is not object'      => ['id',      JsonType::JSON_OBJECT, false ],

            'flot is any'           => ['flot',    JsonType::JSON_ANY,    true  ],
            'flot is int'           => ['flot',    JsonType::JSON_INT,    true  ],
            'flot is float'         => ['flot',    JsonType::JSON_FLOAT,  true  ],
            'flot is string'        => ['flot',    JsonType::JSON_STRING, true  ],

            'flot is not bool'      => ['flot',    JsonType::JSON_BOOL,   false ],
            'flot is not array'     => ['flot',    JsonType::JSON_ARRAY,  false ],
            'flot is not object'    => ['flot',    JsonType::JSON_OBJECT, false ],
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
