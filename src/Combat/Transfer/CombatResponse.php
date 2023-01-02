<?php

declare(strict_types=1);

namespace GC\Combat\Transfer;

final class CombatResponse
{
    public function __construct(
        public readonly Battle $battleBefore,
        public readonly Battle $battleAfter,
        public readonly BattleConfig $battleConfig,
    ) {
    }
}
