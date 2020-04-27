<?php

declare(strict_types=1);

namespace JsonMarshler\Json;

/**
 * Base class unifying all JSON types, both scalar and complex.
 */
abstract class JsonType
{
    public const JSON_ANY    = '';
    public const JSON_STRING = 'string';
    public const JSON_INT    = 'integer';
    public const JSON_FLOAT  = 'double';
    public const JSON_BOOL   = 'bool';
    public const JSON_ARRAY  = 'array';
    public const JSON_OBJECT = 'object';
}
