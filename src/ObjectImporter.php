<?php

declare(strict_types=1);

namespace JsonMarshler;

use JsonMarshler\Importer\ValidationErrors;
use LogicException;
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
     * Validates that a given JSON blob can successfully be imported
     * using this importer.
     *
     * A {@link ValidationErrors} collection is always returned, and
     * the calling code can check for errors with
     * {@link ValidationErrors::hasErrors()}.
     *
     * If the returned value contains no errors, then calling
     * {@link ObjectImporter::import()} with the same arguments *must*
     * always result in an valid imported object being returned.
     *
     * @param stdClass $sourceData The JSON blob to validate.
     *
     * @return ValidationErrors
     */
    public function validate(stdClass $sourceData): ValidationErrors;

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
     * @param stdClass $sourceData The JSON blob to import.
     *
     * @return T
     *
     * @throws LogicException If a validation error would have occurred.
     */
    public function import(stdClass $sourceData): object;
}
