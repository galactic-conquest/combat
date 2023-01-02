<?php

declare(strict_types=1);

namespace GC\Combat\Business\Processor\Combat\Plugin;

use GC\Combat\Transfer\CombatResponse;

use function round;

final class CarrierCombatPlugin implements CombatPluginInterface
{
    public function shouldExecute(CombatResponse $combatResponse): bool
    {
        return $combatResponse->battleConfig->isBattleMode();
    }

    public function execute(CombatResponse $combatResponse): CombatResponse
    {
        $unitCollection = $combatResponse->battleConfig->unitCollection;

        foreach ($combatResponse->battleAfter->getAllAttackerAndDefenderFleets()->getAll() as $fleet) {
            if (!$fleet->fleetConfig->calculateCarrierCapacityLosses) {
                continue;
            }

            $carrierConsumption = $fleet->units->countCarrierConsumption($unitCollection);

            if ($carrierConsumption <= 0) {
                continue;
            }

            $carrierCapacity = $fleet->units->countCarrierCapacity($unitCollection);

            if ($carrierConsumption <= $carrierCapacity) {
                continue;
            }

            $insufficientCapacity = $carrierConsumption - $carrierCapacity;

            foreach ($fleet->units->getAll() as $unitId => $number) {
                if ($unitCollection->getById($unitId)->carrierConsumption <= 0) {
                    continue;
                }

                $lostUnits = (int) round($insufficientCapacity * ($number / $carrierConsumption));

                $fleet->losses->add($unitId, $lostUnits);
                $fleet->units->sub($unitId, $lostUnits);
                $fleet->carrierCapacityLosses->add($unitId, $lostUnits);
            }
        }

        return $combatResponse;
    }
}
