<?php

declare(strict_types=1);

namespace JsonMarshler\Validation\Error;

use PHPUnit\Framework\TestCase;

/**
 * Test for {@link FieldMissingError}
 */
class FieldMissingErrorTest extends TestCase
{
    /**
     * The field name to use for the tests.
     */
    private const FIELD = 'test';

    /**
     * Test for {@link FieldMissingError}
     *
     * @return void
     *
     * @covers \JsonMarshler\Validation\Error\FieldMissingError::__construct
     *
     * @uses \JsonMarshler\Validation\Error\ValidationError
     */
    public function testConstruct(): void
    {
        $error = new FieldMissingError(self::FIELD);
        self::assertSame(self::FIELD, $error->getField());
        self::assertSame('Field not present in object', $error->getProblem());
    }
}
