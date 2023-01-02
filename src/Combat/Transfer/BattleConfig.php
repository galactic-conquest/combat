<?php

declare(strict_types=1);

namespace GC\Combat\Transfer;

final class BattleConfig
{
    final public const MODE_BATTLE = 0;

    final public const DEFAULT_SALVAGE_TARGET_RATIO = 0.4;
    final public const DEFAULT_SALVAGE_DEFENDER_RATIO = 0.2;
    final public const DEFAULT_EXTRACTOR_STEAL_RATIO = 0.1;

    public function __construct(
        public readonly int $mode,
        public readonly UnitCollection $unitCollection,
        public readonly float $salvageTargetRatio,
        public readonly float $salvageDefenderRatio,
        public readonly float $extractorStealRatio,
    ) {
    }

    public function isPreStrikeMode(): bool
    {
        return $this->mode !== self::MODE_BATTLE;
    }

    public function isBattleMode(): bool
    {
        return $this->mode === self::MODE_BATTLE;
    }
}
