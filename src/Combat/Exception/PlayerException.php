<?php

declare(strict_types=1);

namespace GC\Combat\Exception;

use Exception;

use function sprintf;

final class PlayerException extends Exception implements CombatExceptionInterface
{
    public static function forMissingPlayer(string $playerId): PlayerException
    {
        return new self(sprintf('Player with given id "%s" not found.', $playerId));
    }
}
