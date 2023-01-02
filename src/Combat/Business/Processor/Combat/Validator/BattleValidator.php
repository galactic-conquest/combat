<?php

declare(strict_types=1);

namespace GC\Combat\Business\Processor\Combat\Validator;

use GC\Combat\Exception\UnitException;
use GC\Combat\Transfer\Battle;
use GC\Combat\Transfer\BattleConfig;

final class BattleValidator implements BattleValidatorInterface
{
    public function ensureConsistentBattleData(Battle $battle, BattleConfig $battleConfig): void
    {
        $this->ensureValidUnitIdsInFleets($battle, $battleConfig);
        $this->ensureValidBattleConfigCombatSettings($battleConfig);
    }

    private function ensureValidUnitIdsInFleets(Battle $battle, BattleConfig $battleConfig): void
    {
        foreach ($battle->getAllFleets()->getAll() as $fleet) {
            foreach ($fleet->units->getAll() as $unitId => $number) {
                if (! $battleConfig->unitCollection->has($unitId)) {
                    throw UnitException::forMissingUnit($unitId);
                }
            }
        }
    }

    private function ensureValidBattleConfigCombatSettings(BattleConfig $battleConfig): void
    {
        foreach ($battleConfig->unitCollection->getAll() as $unit) {
            foreach ($unit->combats->getAll() as $combatSetting) {
                if (! $battleConfig->unitCollection->has($combatSetting->targetUnitId)) {
                    throw UnitException::forMissingUnit($combatSetting->targetUnitId);
                }
            }
        }
    }
}
