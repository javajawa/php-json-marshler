<?php

declare(strict_types=1);

namespace JsonMarshler\Importer\TestDataStructures;

/**
 * Simple test object with two scalar non-nullable values.
 */
class TwoScalarFieldWithNullableObject
{
    /**
     * The 'user' ID.
     *
     * @var int
     */
    public int $userId;

    /**
     * The 'user's' name.
     *
     * @var string
     */
    public ?string $name;
}
