<?php

declare(strict_types=1);

namespace GC\Combat\Transfer;

class UnitCombat
{
    public function __construct(
        public string $unitId,
        public string $targetUnitId,
        public float $ratio,
        public float $attackPower,
        public int $mode,
    ) {
    }
}
