<?php

declare(strict_types=1);

namespace JsonMarshler\Importer;

use JsonMarshler\Importer\TestDataStructures\TwoScalarFieldObject;
use JsonMarshler\Importer\TestDataStructures\TwoScalarFieldWithNullableObject;
use JsonMarshler\Validation\Error\FieldMissingError;
use JsonMarshler\Validation\Error\UnexpectedFieldError;
use JsonMarshler\Validation\Error\ValidationError;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the ClassReflectionImporter
 */
class ClassReflectionImporterTest extends TestCase
{
    /**
     * Test with an object with no nullable fields.
     *
     * @param string                    $json      Input JSON.
     * @param bool                      $exact     Whether to check for extra fields.
     * @param TwoScalarFieldObject|null $expected  Expected imported object.
     * @param ValidationError           ...$errors List of errors to match.
     *
     * @return void
     *
     * @dataProvider dataSetTwoFields
     *
     * @covers \JsonMarshler\Importer\ClassReflectionImporter
     *
     * @uses \JsonMarshler\Importer\RuleBasedImporter
     * @uses \JsonMarshler\Importer\RuleBasedImporter
     * @uses \JsonMarshler\Validation\ValidationErrors
     * @uses \JsonMarshler\Validation\Error\FieldMissingError
     * @uses \JsonMarshler\Validation\Error\FieldTypeError
     * @uses \JsonMarshler\Validation\Error\UnexpectedFieldError
     * @uses \JsonMarshler\Validation\Error\ValidationError
     * @uses \JsonMarshler\Validation\Rules\FieldExistsRule
     * @uses \JsonMarshler\Validation\Rules\FieldExistsOrNullRule
     * @uses \JsonMarshler\Validation\Rules\NoOtherFields
     * @uses \JsonMarshler\Validation\Rules\ValidationRule
     */
    public function testTwoField(
        string $json,
        bool $exact,
        ?TwoScalarFieldObject $expected,
        ValidationError ...$errors
    ): void
    {
        $data = json_decode($json, false, JSON_THROW_ON_ERROR);

        $importer = new ClassReflectionImporter(TwoScalarFieldObject::class);

        if ($exact) {
            $importer->onlyAllowExactFields();
        }

        $validity = $importer->validate($data);

        static::assertCount(count($errors), $validity, $validity->__toString());

        foreach ($validity as $index => $error) {
            static::assertInstanceOf(get_class($errors[$index]), $error);
            static::assertSame($errors[$index]->getField(), $error->getField());
            static::assertSame($errors[$index]->getProblem(), $error->getProblem());
        }

        if (count($errors) !== 0) {
            return;
        }

        $object = $importer->import($data);
        static::assertEquals($expected, $object);
    }

    /**
     * Test with an object with a nullable fields.
     *
     * @param string                                $json      Input JSON.
     * @param bool                                  $exact     Whether to check for extra fields.
     * @param TwoScalarFieldWithNullableObject|null $expected  Expected imported object.
     * @param ValidationError                       ...$errors List of errors to match.
     *
     * @return void
     *
     * @dataProvider dataSetTwoFieldsNullable
     *
     * @covers \JsonMarshler\Importer\ClassReflectionImporter
     *
     * @uses \JsonMarshler\Importer\RuleBasedImporter
     * @uses \JsonMarshler\Importer\RuleBasedImporter
     * @uses \JsonMarshler\Validation\ValidationErrors
     * @uses \JsonMarshler\Validation\Error\FieldMissingError
     * @uses \JsonMarshler\Validation\Error\FieldTypeError
     * @uses \JsonMarshler\Validation\Error\UnexpectedFieldError
     * @uses \JsonMarshler\Validation\Error\ValidationError
     * @uses \JsonMarshler\Validation\Rules\FieldExistsRule
     * @uses \JsonMarshler\Validation\Rules\FieldExistsOrNullRule
     * @uses \JsonMarshler\Validation\Rules\NoOtherFields
     * @uses \JsonMarshler\Validation\Rules\ValidationRule
     */
    public function testTwoFieldNullable(
        string $json,
        bool $exact,
        ?TwoScalarFieldWithNullableObject $expected,
        ValidationError ...$errors
    ): void
    {
        $data = json_decode($json, false, JSON_THROW_ON_ERROR);

        $importer = new ClassReflectionImporter(TwoScalarFieldWithNullableObject::class);

        if ($exact) {
            $importer->onlyAllowExactFields();
        }

        $validity = $importer->validate($data);

        static::assertCount(count($errors), $validity, $validity->__toString());

        foreach ($validity as $index => $error) {
            static::assertInstanceOf(get_class($errors[$index]), $error);
            static::assertSame($errors[$index]->getField(), $error->getField());
            static::assertSame($errors[$index]->getProblem(), $error->getProblem());
        }

        if (count($errors) !== 0) {
            return;
        }

        $object = $importer->import($data);
        static::assertEquals($expected, $object);
    }

    /**
     * Gets the data for the non-nullable object tests.
     *
     * @return array<string, array<string|bool|TwoScalarFieldObject|ValidationError|null>>
     */
    public function dataSetTwoFields(): array
    {
        return [
            'Correct wo/Null'  => [
                '{"userId":1, "name":"benedict"}',
                true,
                $this->expectedTwoFieldObject(1, "benedict"),
            ],
            'Correct w/Null'   => [
                '{"userId":1, "name":null}',
                true,
                null,
                new FieldMissingError('name'),
            ],
            'Correct wo/field' => [
                '{"userId":1}',
                true,
                null,
                new FieldMissingError('name'),
            ],
            'Correct w/extra1' => [
                '{"userId":1, "name": "benedict", "foo": ""}',
                true,
                null,
                new UnexpectedFieldError('foo'),
            ],
            'Correct w/extra2' => [
                '{"userId":1, "name": "benedict", "foo": ""}',
                false,
                $this->expectedTwoFieldObject(1, "benedict"),
            ],
        ];
    }

    /**
     * Gets the data for the nullable object tests.
     *
     * @return array<string, array<string|bool|TwoScalarFieldWithNullableObject|ValidationError|null>>
     */
    public function dataSetTwoFieldsNullable(): array
    {
        return [
            'Correct wo/Null'  => [
                '{"userId":1, "name":"benedict"}',
                true,
                $this->expectedNullableObject(1, "benedict"),
            ],
            'Correct w/Null'   => [
                '{"userId":1, "name":null}',
                true,
                $this->expectedNullableObject(1, null),
            ],
            'Correct wo/field' => [
                '{"userId":1}',
                true,
                null,
                new FieldMissingError('name'),
            ],
            'Correct w/extra1' => [
                '{"userId":1, "name":"benedict", "foo": ""}',
                true,
                null,
                new UnexpectedFieldError('foo'),
            ],
            'Correct w/extra2' => [
                '{"userId":1, "name":"benedict", "foo": ""}',
                false,
                $this->expectedNullableObject(1, "benedict"),
            ],
        ];
    }

    /**
     * Creates an expected result object
     *
     * @param int    $userId The expected user ID.
     * @param string $name   The expected name.
     *
     * @return TwoScalarFieldObject
     */
    private function expectedTwoFieldObject(int $userId, string $name): TwoScalarFieldObject
    {
        $object         = new TwoScalarFieldObject();
        $object->userId = $userId;
        $object->name   = $name;

        return $object;
    }

    /**
     * Creates an expected result object
     *
     * @param int         $userId The expected user ID.
     * @param string|null $name   The expected name.
     *
     * @return TwoScalarFieldWithNullableObject
     */
    private function expectedNullableObject(int $userId, ?string $name): TwoScalarFieldWithNullableObject
    {
        $object         = new TwoScalarFieldWithNullableObject();
        $object->userId = $userId;
        $object->name   = $name;

        return $object;
    }
}
