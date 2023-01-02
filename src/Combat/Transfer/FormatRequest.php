<?php

declare(strict_types=1);

namespace GC\Combat\Transfer;

final class FormatRequest
{
    public function __construct(
        public readonly CombatResponse $combatResponse,
        public readonly bool $includeConfig = true,
        public readonly bool $includeConfigUnits = false,
        public readonly bool $includeIds = false,
        public readonly bool $useUnitCodeName = true,
    ) {
    }
}
