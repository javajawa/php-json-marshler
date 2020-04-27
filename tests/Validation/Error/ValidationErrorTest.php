<?php

declare(strict_types=1);

namespace JsonMarshler\Validation\Error;

use PHPUnit\Framework\TestCase;

/**
 * Class ValidationErrorTest
 */
class ValidationErrorTest extends TestCase
{
    /**
     * The field name to use for the tests.
     */
    private const FIELD = 'ID';

    /**
     * The problem string to use for the tests.
     */
    private const PROBLEM = 'problem';

    /**
     * Set and get the Field field.
     *
     * @return void
     *
     * @covers \JsonMarshler\Validation\Error\ValidationError::getField
     * @covers \JsonMarshler\Validation\Error\ValidationError::__construct
     */
    public function testGetField(): void
    {
        $error = new class (self::FIELD, self::PROBLEM) extends ValidationError {
        };
        self::assertSame(self::FIELD, $error->getField());
    }

    /**
     * Set and get the Problem field.
     *
     * @return void
     *
     * @covers \JsonMarshler\Validation\Error\ValidationError::getProblem
     * @covers \JsonMarshler\Validation\Error\ValidationError::__construct
     */
    public function testGetProblem(): void
    {
        $error = new class (self::FIELD, self::PROBLEM) extends ValidationError {
        };
        self::assertSame(self::PROBLEM, $error->getProblem());
    }

    /**
     * Set and get the Problem field.
     *
     * @return void
     *
     * @covers \JsonMarshler\Validation\Error\ValidationError::jsonSerialize
     *
     * @uses \JsonMarshler\Validation\Error\ValidationError::__construct
     */
    public function testJsonSerialize(): void
    {
        $error = new class (self::FIELD, self::PROBLEM) extends ValidationError {
        };
        self::assertSame(['field' => self::FIELD, 'problem' => self::PROBLEM], $error->jsonSerialize());
    }

    /**
     * Set and get the Problem field.
     *
     * @return void
     *
     * @covers \JsonMarshler\Validation\Error\ValidationError::__toString
     *
     * @uses \JsonMarshler\Validation\Error\ValidationError::__construct
     * @uses \JsonMarshler\Validation\Error\ValidationError::getField
     * @uses \JsonMarshler\Validation\Error\ValidationError::getProblem
     */
    public function testToString(): void
    {
        $error = new class (self::FIELD, self::PROBLEM) extends ValidationError {
        };

        $string = $error->__toString();

        self::assertStringContainsString($error->getField(), $string);
        self::assertStringContainsString($error->getProblem(), $string);
    }
}
