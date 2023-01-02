<?php

declare(strict_types=1);

namespace GC\Combat\Business\Processor\Combat\Plugin;

use GC\Combat\Business\Processor\Combat\Plugin\Strike\FleetState;
use GC\Combat\Business\Processor\Combat\Plugin\Strike\FleetUnitPrecisionBag;
use GC\Combat\Business\Processor\Combat\Plugin\Strike\StrikeState;
use GC\Combat\Transfer\CombatResponse;
use GC\Combat\Transfer\Unit;
use GC\Combat\Transfer\UnitCollection;
use GC\Combat\Transfer\UnitCombat;

use function ceil;
use function floor;
use function max;
use function min;
use function round;

final class StrikeCombatPlugin implements CombatPluginInterface
{
    public function shouldExecute(CombatResponse $combatResponse): bool
    {
        return true;
    }

    public function execute(CombatResponse $combatResponse): CombatResponse
    {
        $isPreStrikeMode = $combatResponse->battleConfig->isPreStrikeMode();

        $unitCollection = $combatResponse->battleConfig->unitCollection;

        $stateAttacker = new StrikeState(
            $combatResponse->battleBefore->getAllAttackerFleets(),
            $combatResponse->battleAfter->getAllAttackerFleets()
        );

        $stateDefender = new StrikeState(
            $combatResponse->battleBefore->getAllDefenderAndTargetFleets(),
            $combatResponse->battleAfter->getAllDefenderAndTargetFleets()
        );

        $fleetStateAttacker = new FleetState($stateAttacker->fleetsBefore->getTotalUnits());
        $fleetStateDefender = new FleetState($stateDefender->fleetsBefore->getTotalUnits());

        foreach ($unitCollection->getAll() as $attackingUnit) {
            if ($attackingUnit->combats->isEmpty()) {
                continue;
            }

            $stateAttacker->setAttackingUnit($attackingUnit, $fleetStateAttacker);
            $stateDefender->setAttackingUnit($attackingUnit, $fleetStateDefender);
            $strikes = $this->calculateStrikes($attackingUnit, $stateAttacker, $stateDefender);

            for (; $strikes > 0; $strikes--) {
                $stateAttacker->currentStrike++;
                $stateDefender->currentStrike++;

                foreach ($attackingUnit->combats->getByMode($combatResponse->battleConfig->mode) as $combatSetting) {
                    if (!$isPreStrikeMode) {
                        $stateAttacker->setCombatSetting($combatSetting);
                        $destroyed = $this->calculateDestroyed($stateAttacker, $fleetStateDefender);
                        $this->destroy($fleetStateAttacker, $fleetStateDefender, $combatSetting, $destroyed);
                        $stateAttacker->recalculateShots($destroyed);
                        $this->splitDestroyed($stateAttacker, $fleetStateAttacker, $destroyed);
                    }

                    $stateDefender->setCombatSetting($combatSetting, $isPreStrikeMode);
                    $destroyed = $this->calculateDestroyed($stateDefender, $fleetStateAttacker);
                    $this->destroy($fleetStateDefender, $fleetStateAttacker, $combatSetting, $destroyed);
                    $stateDefender->recalculateShots($destroyed);
                    $this->splitDestroyed($stateDefender, $fleetStateDefender, $destroyed);
                }

                $stateAttacker->shotsRemaining = $stateAttacker->shots;
                $stateDefender->shotsRemaining = $stateDefender->shots;
            }
        }

        if ($isPreStrikeMode) {
            // TODO this maybe in strike not at the end of fight.
            $this->calculateCarrierCapacityLosses($unitCollection, $fleetStateAttacker->after);
            $this->calculateCarrierCapacityLosses($unitCollection, $fleetStateDefender->after);
        }

        foreach ($combatResponse->battleConfig->unitCollection->getAll() as $unit) {
            $this->splitLosses($unit, $stateAttacker, $fleetStateAttacker);
            $this->splitLosses($unit, $stateDefender, $fleetStateDefender);
        }

        return $combatResponse;
    }

    private function calculateStrikes(Unit $unit, StrikeState $stateAttacker, StrikeState $stateDefender): int
    {
        $minRatio = $unit->combats->getMinRatio();

        if ($minRatio === null || ($stateAttacker->shots === 0.0 && $stateDefender->shots === 0.0)) {
            return 0;
        }

        return (int) ceil(1 / $minRatio) + 3;
    }

    private function calculateDestroyed(StrikeState $strikeState, FleetState $fleetState): int
    {
        $maxKillable = floor(
            round($strikeState->ratio * $strikeState->shotsRemaining * $strikeState->combatSetting->attackPower, 2)
        );

        $destroyed = (int) min(
            $maxKillable,
            $fleetState->after->get($strikeState->combatSetting->targetUnitId),
            round($strikeState->shots * $strikeState->combatSetting->attackPower, 4)
        );

        return max($destroyed, 0);
    }

    private function splitDestroyed(StrikeState $strikeState, FleetState $fleetState, int $destroyed): void
    {
        if (! $fleetState->before->has($strikeState->attackingUnit->unitId)) {
            return;
        }

        $attackingUnitsNumberTotal = $fleetState->before->get($strikeState->attackingUnit->unitId);

        foreach ($strikeState->fleetsBefore->getAll() as $fleetBefore) {
            if ($attackingUnitsNumberTotal <= 0) {
                continue;
            }

            $attackingNumberUnitsFleet = $fleetBefore->units->get($strikeState->attackingUnit->unitId);
            $responsibleDestruction = $destroyed * $attackingNumberUnitsFleet / $attackingUnitsNumberTotal;

            if ($responsibleDestruction > 0.0) {
                $strikeState->fleetsAfter
                    ->getFleetById($fleetBefore->fleetId)
                    ->destroyed
                    ->add($strikeState->combatSetting->targetUnitId, (int) round($responsibleDestruction));
            }
        }
    }

    private function splitLosses(Unit $unit, StrikeState $strikeState, FleetState $fleetState): void
    {
        if (! $fleetState->before->has($unit->unitId)) {
            return;
        }

        $numberOfUnitsBeforeTotal = $fleetState->before->get($unit->unitId);
        $numberOfUnitsAfterTotal = $fleetState->after->get($unit->unitId);

        foreach ($strikeState->fleetsBefore->getAll() as $fleet) {
            if (! $fleet->units->has($unit->unitId)) {
                continue;
            }

            $numberOfUnitsBeforeFleet = $fleet->units->get($unit->unitId);

            $numberOfLeftUnits = (int) max(
                round($numberOfUnitsAfterTotal * $numberOfUnitsBeforeFleet / $numberOfUnitsBeforeTotal),
                0
            );

            $numberOfLostUnits = $numberOfUnitsBeforeFleet - $numberOfLeftUnits;

            if ($numberOfLostUnits <= 0) {
                continue;
            }

            $fleetAfter = $strikeState->fleetsAfter->getFleetById($fleet->fleetId);
            $fleetAfter->losses->add($unit->unitId, $numberOfLostUnits);
            $fleetAfter->units->sub($unit->unitId, $numberOfLostUnits);
        }
    }

    private function destroy(
        FleetState $currentAttacker,
        FleetState $currentDefender,
        UnitCombat $combatSetting,
        int $destroyed
    ): void {
        if ($destroyed > 0) {
            $currentDefender->losses->add($combatSetting->targetUnitId, $destroyed);
            $currentDefender->after->sub($combatSetting->targetUnitId, $destroyed);
            $currentAttacker->destroyed->add($combatSetting->targetUnitId, $destroyed);
        }
    }

    private function calculateCarrierCapacityLosses(UnitCollection $unitCollection, FleetUnitPrecisionBag $units): void
    {
        // TODO calculate carrier space losses due to pre strike
    }
}
