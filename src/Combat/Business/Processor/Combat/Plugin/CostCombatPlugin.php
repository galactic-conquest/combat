<?php

declare(strict_types=1);

namespace GC\Combat\Business\Processor\Combat\Plugin;

use GC\Combat\Transfer\CombatResponse;
use GC\Combat\Transfer\FleetCollection;
use GC\Combat\Transfer\UnitCollection;

final class CostCombatPlugin implements CombatPluginInterface
{
    public function shouldExecute(CombatResponse $combatResponse): bool
    {
        return true;
    }

    public function execute(CombatResponse $combatResponse): CombatResponse
    {
        $this->calculateCost(
            $combatResponse->battleAfter->getAllFleets(),
            $combatResponse->battleConfig->unitCollection,
        );

        return $combatResponse;
    }

    private function calculateCost(FleetCollection $fleetCollection, UnitCollection $unitCollection): void
    {
        foreach ($fleetCollection->getAll() as $fleet) {
            $fleet->cost->add($fleet->losses->calculateCost($unitCollection));
        }
    }
}
