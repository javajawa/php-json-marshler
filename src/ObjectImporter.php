<?php declare(strict_types = 1);

namespace JsonMarshler;

use JsonMarshler\Validation\ValidationErrors;
use stdClass;

/**
 * Interface ObjectImporter
 *
 * @package Revtracker
 *
 * @template T of object
 */
interface ObjectImporter
{
    /**
     * Validates that
     *
     * @param stdClass $sourceData
     *
     * @return ValidationErrors
     */
    public function validate(stdClass $sourceData): ValidationErrors;

    /**
     *
     * @param stdClass $sourceData
     *
     * @return T
     */
    public function import(stdClass $sourceData): object;
}
