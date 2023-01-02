<?php

declare(strict_types=1);

namespace GC\Combat\Transfer;

use GC\Combat\Business\UnitGroup\UnitGroup;
use GC\Combat\Business\Resource\ResourceBag;

class Unit
{
    public function __construct(
        public string $unitId,
        public string $name,
        public string $codeName,
        public string $unitClass,
        public string $description,
        public UnitGroup $group,
        public ResourceBag $cost,
        public int $ticksCost,
        public int $carrierCapacity,
        public int $carrierConsumption,
        public int $extractorSteal,
        public int $extractorGuard,
        public int $scanRelaisFactor,
        public int $scanBlockerFactor,
        public int $cargo,
        public UnitCombatCollection $combats = new UnitCombatCollection(),
    ) {
    }
}
