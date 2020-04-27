<?php

declare(strict_types=1);

namespace JsonMarshler\Validation\Error;

use PHPUnit\Framework\TestCase;

/**
 * Test for {@link UnexpectedFieldError}
 */
class UnexpectedFieldErrorTest extends TestCase
{
    /**
     * The field name to use for the tests.
     */
    private const FIELD = 'test';

    /**
     * Test for {@link UnexpectedFieldError}
     *
     * @return void
     *
     * @covers \JsonMarshler\Validation\Error\UnexpectedFieldError::__construct
     *
     * @uses \JsonMarshler\Validation\Error\ValidationError
     */
    public function testConstruct(): void
    {
        $error = new UnexpectedFieldError(self::FIELD);
        self::assertSame(self::FIELD, $error->getField());
        self::assertSame('Unexpected field in object', $error->getProblem());
    }
}
