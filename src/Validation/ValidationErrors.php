<?php

declare(strict_types=1);

namespace JsonMarshler\Validation;

use Countable;
use Iterator;
use JsonMarshler\Validation\Error\ValidationError;

/**
 * A mutable collection of {@link ValidationError} objects.
 *
 * @implements Iterator<int, ValidationError>
 */
class ValidationErrors implements Countable, Iterator
{
    /**
     * The backing storage of the errors.
     *
     * @var array<int, ValidationError>
     */
    private array $buffer = [];

    /**
     * Adds one or more errors to this collection.
     *
     * @param ValidationError ...$errors A list of errors to add.
     *
     * @return void
     */
    public function add(ValidationError ...$errors): void
    {
        array_push($this->buffer, ...$errors);
    }

    /**
     * Convenience method to check whether this collection currently contains
     * any errors.
     *
     *   $foo->hasErrors() === count($foo) > 0
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return count($this->buffer) > 0;
    }

    /**
     * Gets the current item in the iterator.
     *
     * @inheritDoc
     *
     * @return ValidationError
     */
    public function current(): ValidationError
    {
        $value = current($this->buffer);

        assert($value instanceof ValidationError);

        return $value;
    }

    /**
     * Moves the iterator to the next element.
     *
     * @inheritDoc
     *
     * @return void
     */
    public function next(): void
    {
        next($this->buffer);
    }

    /**
     * The index (key) of the current item in the iterator,
     * or null if the iterator is not currently valid.
     *
     * @inheritDoc
     *
     * @return int|null
     */
    public function key(): ?int
    {
        return key($this->buffer);
    }

    /**
     * Whether the iterator is pointing at a valid item.
     *
     * @inheritDoc
     *
     * @return bool
     */
    public function valid(): bool
    {
        return key($this->buffer) !== null;
    }

    /**
     * Resets the iterator to the beginning.
     *
     * @inheritDoc
     *
     * @return void
     */
    public function rewind(): void
    {
        reset($this->buffer);
    }

    /**
     * Counts the number of errors that have been recorded.
     *
     * @inheritDoc
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->buffer);
    }
}
