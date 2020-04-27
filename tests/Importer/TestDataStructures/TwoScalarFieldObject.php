<?php

declare(strict_types=1);

namespace JsonMarshler\Importer\TestDataStructures;

/**
 * Simple test object with two scalar non-nullable values.
 */
class TwoScalarFieldObject
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
    public string $name;

    /**
     * Test property to ensure that statics are ignored.
     *
     * @var string
     */
    public static string $staticField = 'This is here to complete code coverage';
}
