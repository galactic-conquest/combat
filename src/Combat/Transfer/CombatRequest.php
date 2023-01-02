<?php

declare(strict_types=1);

namespace GC\Combat\Transfer;

final class CombatRequest
{
    public function __construct(
        public readonly Battle $battle,
        public BattleConfig $config,
    ) {
    }
}
