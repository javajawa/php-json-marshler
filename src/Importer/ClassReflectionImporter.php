<?php

declare(strict_types=1);

namespace JsonMarshler\Importer;

use JsonMarshler\Json\JsonType;
use JsonMarshler\Validation\Rules\FieldExistsOrNullRule;
use JsonMarshler\Validation\Rules\FieldExistsRule;
use JsonMarshler\Validation\Rules\NoOtherFields;
use LogicException;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;

use function assert;

/**
 * An importer which automatically generates rules for a class
 * based on the properties of that class.
 *
 * Each property defined in the class will be added as an expected
 * field; if that property has a type annotation, that type will
 * also be verified.
 *
 * The class name supplied must exists, and it must also have a
 * public no-argument constructor.
 *
 * If `onlyAllowExactFields` is called before validation and import,
 * any keys existing in the JSON object which are not part of the
 * class will cause validation errors.
 *
 * @template T of object
 *
 * @extends RuleBasedImporter<T>
 */
class ClassReflectionImporter extends RuleBasedImporter
{
    /**
     * The class reference for instance initialisation.
     *
     * @var ReflectionClass<T>
     */
    private ReflectionClass $class;

    /**
     * The list of properties that we are importing.
     *
     * @var array<ReflectionProperty>
     */
    private array $properties;

    /**
     * Each property defined in the class will be added as an expected
     * field; if that property has a type annotation, that type will
     * also be verified.
     *
     * The class name supplied must exists, and it must also have a
     * public no-argument constructor.
     *
     * @param class-string<T> $class The class name to build an importer for.
     *
     * @throws ReflectionException If the provided class name does not exist.
     */
    public function __construct(string $class)
    {
        $this->class = new ReflectionClass($class);

        $rules = [];

        $this->properties = $this->class->getProperties();

        foreach ($this->properties as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $typeStr  = JsonType::JSON_ANY;
            $nullable = true;

            if ($property->hasType()) {
                $type = $property->getType();

                assert($type instanceof ReflectionNamedType);

                $nullable = $type->allowsNull();
                $typeStr  = $type->getName();

                if ($typeStr === 'int') {
                    $typeStr = JsonType::JSON_INT;
                }
            }

            $class   = $nullable ? FieldExistsOrNullRule::class : FieldExistsRule::class;
            $rules[] = new $class($property->getName(), $typeStr);

            $property->setAccessible(true);
        }

        parent::__construct(...$rules);
    }

    /**
     * Sets an additional rule so that the JSON object being imported must
     * not have any other properties except the ones in this class.
     *
     * @return void
     */
    public function onlyAllowExactFields(): void
    {
        $fields = [];

        foreach ($this->properties as $property) {
            $fields[] = $property->getName();
        }

        $this->addRule(new NoOtherFields(...$fields));
    }

    /**
     * Attempts to import a JSON blob into a specific PHP object.
     *
     * Before using this function, it is recommend to call
     * {@link ObjectImporter::validate()} with the same parameters.
     *
     * If the validation response returns no errors, this function
     * is guaranteed to always return a valid instance of the objct.
     * Otherwise, a LogicException is thrown.
     *
     * @param object $sourceData The JSON blob to import.
     *
     * @return T
     *
     * @throws LogicException If a validation error would have occurred.
     */
    public function import(object $sourceData): object
    {
        $output = $this->class->newInstance();

        foreach ($this->properties as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $property->setValue($output, $sourceData->{$property->getName()});
        }

        return $output;
    }
}
