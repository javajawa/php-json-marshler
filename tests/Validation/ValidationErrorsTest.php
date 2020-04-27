<?php

declare(strict_types=1);

namespace JsonMarshler\Validation;

use JsonMarshler\Validation\Error\FieldMissingError;
use PHPUnit\Framework\TestCase;

use function iterator_to_array;

/**
 * Tests for ValidationErrors
 */
class ValidationErrorsTest extends TestCase
{
    /**
     * Tests all state of a empty ValidationErrors list.
     *
     * @return void
     *
     * @covers \JsonMarshler\Validation\ValidationErrors
     */
    public function testEmptyList(): void
    {
        $list = new ValidationErrors();

        self::assertFalse($list->hasErrors());
        self::assertCount(0, $list);

        $iteratred = iterator_to_array($list);

        self::assertIsArray($iteratred);
        self::assertCount(0, $iteratred);

        $string = $list->__toString();
        static::assertCount(1, explode(PHP_EOL, $string));
    }

    /**
     * Tests one item
     *
     * @return void
     *
     * @covers \JsonMarshler\Validation\ValidationErrors
     *
     * @uses \JsonMarshler\Validation\Error\FieldMissingError
     * @uses \JsonMarshler\Validation\Error\ValidationError
     */
    public function testSequentialAdding(): void
    {
        $list  = new ValidationErrors();
        $rule1 = new FieldMissingError('a');
        $rule2 = new FieldMissingError('a');

        $list->add($rule1);

        $rules = iterator_to_array($list);
        self::assertTrue($list->hasErrors());
        self::assertCount(1, $list);
        self::assertCount(1, $rules);
        self::assertSame($rule1, $rules[0]);

        $list->add($rule2);

        $rules = iterator_to_array($list);
        self::assertTrue($list->hasErrors());
        self::assertCount(2, $list);
        self::assertCount(2, $rules);
        self::assertSame($rule1, $rules[0]);
        self::assertNotSame($rule2, $rules[0]);
        self::assertSame($rule2, $rules[1]);
        self::assertNotSame($rule1, $rules[1]);

        $string = $list->__toString();
        static::assertCount(4, explode(PHP_EOL, $string));
        static::assertStringContainsString($rule1->__toString(), $string);
        static::assertStringContainsString($rule2->__toString(), $string);
    }

    /**
     * Tests one item
     *
     * @return void
     *
     * @covers \JsonMarshler\Validation\ValidationErrors
     *
     * @uses \JsonMarshler\Validation\Error\FieldMissingError
     * @uses \JsonMarshler\Validation\Error\ValidationError
     */
    public function testGroupAdding(): void
    {
        $list  = new ValidationErrors();
        $rule1 = new FieldMissingError('a');
        $rule2 = new FieldMissingError('a');

        $list->add($rule1, $rule2);

        $rules = iterator_to_array($list);
        self::assertTrue($list->hasErrors());
        self::assertCount(2, $list);
        self::assertCount(2, $rules);
        self::assertSame($rule1, $rules[0]);
        self::assertNotSame($rule2, $rules[0]);
        self::assertSame($rule2, $rules[1]);
        self::assertNotSame($rule1, $rules[1]);

        $string = $list->__toString();
        static::assertCount(4, explode(PHP_EOL, $string));
        static::assertStringContainsString($rule1->__toString(), $string);
        static::assertStringContainsString($rule2->__toString(), $string);
    }
}
