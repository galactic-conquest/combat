<?php

declare(strict_types=1);

namespace GC\Combat\Business\Processor\Combat\Plugin;

use GC\Combat\Transfer\BattleConfig;
use GC\Combat\Transfer\CombatResponse;
use GC\Combat\Transfer\FleetCollection;
use GC\Combat\Transfer\Target;
use GC\Combat\Business\Resource\ResourceBag;

use function round;

final class SalvageCombatPlugin implements CombatPluginInterface
{
    public function shouldExecute(CombatResponse $combatResponse): bool
    {
        return $combatResponse->battleConfig->isBattleMode();
    }

    public function execute(CombatResponse $combatResponse): CombatResponse
    {
        $totalCostExtractorLosses = $combatResponse->battleAfter
            ->getAllAttackerFleets()
            ->calculateCostExtractorLosses($combatResponse->battleConfig->unitCollection);

        $totalCostCapacityCapacityLosses = $combatResponse
            ->battleAfter
            ->getAllFleets()
            ->calculateCostCarrierCapacityLosses($combatResponse->battleConfig->unitCollection);

        $salvageable = $combatResponse->battleAfter->getTotalCostForAllFleets();
        $salvageable->sub($totalCostExtractorLosses);
        $salvageable->sub($totalCostCapacityCapacityLosses);

        if ($salvageable->isEmpty()) {
            return $combatResponse;
        }

        $this->calculateTarget(
            $combatResponse->battleAfter->target,
            $salvageable,
            $combatResponse->battleConfig
        );

        $this->calculateDefenders(
            $combatResponse->battleAfter->defenders->getFleetCollection(),
            $salvageable,
            $totalCostExtractorLosses,
            $combatResponse->battleConfig,
        );

        return $combatResponse;
    }

    private function calculateTarget(Target $target, ResourceBag $salvageable, BattleConfig $battleConfig): void
    {
        $target->salvaged->addMetal((int) round($salvageable->getMetal() * $battleConfig->salvageTargetRatio, 4));
        $target->salvaged->addCrystal((int) round($salvageable->getCrystal() * $battleConfig->salvageTargetRatio, 4));
    }

    private function calculateDefenders(
        FleetCollection $defenderFleets,
        ResourceBag $salvageable,
        ResourceBag $totalCostExtractorLosses,
        BattleConfig $battleConfig,
    ): void {
        $totalCostDestroyed = $defenderFleets->calculateCostDestroyed($battleConfig->unitCollection);

        foreach ($defenderFleets->getAll() as $fleet) {
            $destroyedByFleet = $fleet->destroyed->calculateCost($battleConfig->unitCollection)->getTotal();

            $totalDestroyed = $totalCostDestroyed->getTotal() + $totalCostExtractorLosses->getTotal();

            if ($totalDestroyed <= 0) {
                continue;
            }

            $share = $destroyedByFleet / $totalDestroyed;

            $metal = round($share * ($battleConfig->salvageDefenderRatio * $salvageable->getMetal()), 4);
            $fleet->salvaged->addMetal((int) $metal);

            $crystal = round($share * ($battleConfig->salvageDefenderRatio * $salvageable->getCrystal()), 4);
            $fleet->salvaged->addCrystal((int) $crystal);
        }
    }
}
