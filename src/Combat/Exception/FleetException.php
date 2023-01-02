<?php

declare(strict_types=1);

namespace GC\Combat\Exception;

use Exception;

use function sprintf;

final class FleetException extends Exception implements CombatExceptionInterface
{
    public static function forMissingFleet(string $fleetId): FleetException
    {
        return new self(sprintf('Fleet with given id "%s" not found.', $fleetId));
    }
}
