<?php

declare(strict_types=1);

namespace GC\Combat\Business\Processor\Combat\Plugin\Strike;

use GC\Combat\Transfer\FleetCollection;
use GC\Combat\Transfer\Unit;
use GC\Combat\Transfer\UnitCombat;

use function in_array;
use function max;
use function min;

final class StrikeState
{
    public Unit $attackingUnit;
    public UnitCombat $combatSetting;

    /**
     * @param array<string> $unusedRatioTargetCalculated
     */
    public function __construct(
        public readonly FleetCollection $fleetsBefore,
        public readonly FleetCollection $fleetsAfter,
        public int $currentStrike = 0,
        public float $shots = 0.0,
        public float $shotsRemaining = 0.0,
        public array $unusedRatioTargetCalculated = [],
        public float $unusedRatio = 0.0,
        public float $ratio = 0.0,
    ) {
    }

    public function setAttackingUnit(Unit $attackingUnit, FleetState $fleetState): void
    {
        $numberOfUnits = $fleetState->before->get($attackingUnit->unitId);

        $this->attackingUnit = $attackingUnit;
        $this->shots = $numberOfUnits;
        $this->shotsRemaining = $numberOfUnits;
        $this->unusedRatioTargetCalculated = [];
        $this->unusedRatio = 0.0;
        $this->ratio = 0.0;
        $this->currentStrike = 0;
    }

    public function setCombatSetting(UnitCombat $combatSetting, bool $isPreStrike = false): void
    {
        $this->combatSetting = $combatSetting;
        $this->ratio = $combatSetting->ratio;
        $this->unusedRatio = min(1 - $this->ratio, $this->unusedRatio);

        if ($this->currentStrike > 3 && $isPreStrike !== true) {
            $this->ratio += $this->unusedRatio;
        }
    }

    public function recalculateShots(int $destroyedUnits): void
    {
        $usedShots = $destroyedUnits / $this->combatSetting->attackPower;
        $this->shots = max($this->shots - $usedShots, 0.0);

        $isAlreadyAdjusted = in_array($this->combatSetting->targetUnitId, $this->unusedRatioTargetCalculated, true);

        if ($this->currentStrike <= 3 || $isAlreadyAdjusted) {
            return;
        }

        if ($this->shots > 0.0) {
            $this->unusedRatio += max($this->combatSetting->ratio - $usedShots / $this->shotsRemaining, 0.0);
        } else {
            $this->unusedRatio += $this->combatSetting->ratio;
        }

        $this->unusedRatioTargetCalculated[] = $this->combatSetting->targetUnitId;
    }
}
