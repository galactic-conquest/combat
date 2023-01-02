<?php

declare(strict_types=1);

namespace GC\Combat\Transfer;

final class Player
{
    public function __construct(
        public readonly string $playerId,
        public readonly string $name,
        public readonly FleetCollection $fleets,
        public readonly ?int $galaxyNumber = null,
        public readonly ?int $galaxyPosition = null,
    ) {
    }
}
