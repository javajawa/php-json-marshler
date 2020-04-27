<?php

declare(strict_types=1);

namespace JsonMarshler\Validation\Error;

use PHPUnit\Framework\TestCase;

/**
 * Simple test for {@link FieldTypeError}.
 */
class FieldTypeErrorTest extends TestCase
{
    /**
     * The field name to use for the tests.
     */
    private const FIELD = 'ID';

    /**
     * The test type wanted for the object.
     */
    private const EXPECTED_TYPE = 'ID';

    /**
     * The test type found in the JSON.
     */
    private const FOUND_TYPE = 'ID';

    /**
     * Test for {@link UnexpectedFieldError}
     *
     * @return void
     *
     * @covers \JsonMarshler\Validation\Error\FieldTypeError
     *
     * @uses \JsonMarshler\Validation\Error\ValidationError
     */
    public function test(): void
    {
        $error = new FieldTypeError(self::FIELD, self::EXPECTED_TYPE, self::FOUND_TYPE);

        self::assertSame(self::FIELD, $error->getField());
        self::assertSame(self::EXPECTED_TYPE, $error->getExpectedType());
        self::assertSame(self::FOUND_TYPE, $error->getFoundType());
    }
}
