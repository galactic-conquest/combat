<?php

declare(strict_types=1);

namespace GC\Combat\Exception;

use Exception;

use function sprintf;

final class UnitException extends Exception implements CombatExceptionInterface
{
    public static function forMissingUnit(string $unitId): UnitException
    {
        return new self(sprintf('Unit with given id %s not found in battle config.', $unitId));
    }

    public static function forUnitNotFound(string $unitId): CombatExceptionInterface
    {
        return new self(sprintf('Unit with given id "%s" not found.', $unitId));
    }
}
