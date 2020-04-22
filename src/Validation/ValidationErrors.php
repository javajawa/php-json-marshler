<?php declare(strict_types = 1);

namespace JsonMarshler\Validation;

use Countable;
use Iterator;

/**
 * A mutable collection of {@link ValidationError} objects.
 *
 * @implements Iterator<int, ValidationError>
 */
class ValidationErrors implements Countable, Iterator
{
    /** @var array<int, ValidationError> */
    private array $buffer = [];

    /**
     * Adds one or more errors to this collection.
     *
     * @param ValidationError ...$errors
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
     * @inheritDoc
     *
     * @return void
     */
    public function next(): void
    {
        next($this->buffer);
    }

    /**
     * @inheritDoc
     *
     * @return int|null
     */
    public function key(): ?int
    {
        return key($this->buffer);
    }

    /**
     * @inheritDoc
     *
     * @return bool
     */
    public function valid(): bool
    {
        return key($this->buffer) !== null;
    }

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function rewind(): void
    {
        reset($this->buffer);
    }

    /**
     * @inheritDoc
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->buffer);
    }
}
