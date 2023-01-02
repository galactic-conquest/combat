<?php

declare(strict_types=1);

namespace GC\Combat\Business\Processor\Combat\Plugin;

use GC\Combat\Business\Extractor\ExtractorBag;
use GC\Combat\Transfer\BattleConfig;
use GC\Combat\Transfer\CombatResponse;
use GC\Combat\Transfer\Fleet;
use GC\Combat\Transfer\FleetCollection;
use GC\Combat\Transfer\Target;
use GC\Combat\Transfer\UnitCollection;

use function ceil;
use function floor;
use function min;
use function round;

final class ExtractorCombatPlugin implements CombatPluginInterface
{
    public function shouldExecute(CombatResponse $combatResponse): bool
    {
        return $combatResponse->battleConfig->isBattleMode();
    }

    public function execute(CombatResponse $combatResponse): CombatResponse
    {
        $unitCollection = $combatResponse->battleConfig->unitCollection;

        $totalStealPotential = $combatResponse->battleAfter
            ->getAllAttackerFleets()
            ->countExtractorStealPotential($unitCollection);

        $totalGuardPotential = $combatResponse->battleAfter
            ->getAllDefenderAndTargetFleets()
            ->countExtractorGuardPotential($unitCollection);

        $maxPotential = $totalStealPotential - $totalGuardPotential;

        if ($maxPotential <= 0) {
            return $combatResponse;
        }

        $stolenExtractorBag = $this->calculateTotalStolen(
            $combatResponse->battleAfter->target,
            $combatResponse->battleConfig,
            $maxPotential,
        );

        if ($stolenExtractorBag->isEmpty()) {
            return $combatResponse;
        }

        $this->setStolenExtractorsToTarget(
            $combatResponse->battleAfter->target,
            $stolenExtractorBag
        );

        $this->splitStolenExtractors(
            $combatResponse->battleAfter->getAllAttackerFleets(),
            $combatResponse->battleConfig->unitCollection,
            $stolenExtractorBag,
            $totalStealPotential,
        );

        return $combatResponse;
    }

    private function calculateTotalStolen(Target $target, BattleConfig $battleConfig, int $maxPotential): ExtractorBag
    {
        $maxPotentialMetal = ceil($maxPotential / 2);
        $maxPotentialCrystal = floor($maxPotential / 2);

        $stolenMetal = min(
            $maxPotentialMetal,
            floor($target->extractors->getMetal() * $battleConfig->extractorStealRatio)
        );

        if ($stolenMetal !== $maxPotentialMetal) {
            $maxPotentialCrystal += $maxPotentialMetal - $stolenMetal;
        }

        $stolenCrystal = min(
            $maxPotentialCrystal,
            floor($target->extractors->getCrystal() * $battleConfig->extractorStealRatio)
        );

        if ($stolenCrystal !== $maxPotentialCrystal) {
            $maxPotentialMetal += $maxPotentialMetal - $stolenCrystal;
            $stolenMetal = min(
                $maxPotentialMetal,
                floor($target->extractors->getMetal() * $battleConfig->extractorStealRatio)
            );
        }

        return new ExtractorBag(
            (int) $stolenMetal,
            (int) $stolenCrystal,
        );
    }

    private function setStolenExtractorsToTarget(Target $target, ExtractorBag $stolenExtractorBag): void
    {
        $target->extractors->subMetal($stolenExtractorBag->getMetal());
        $target->extractors->subCrystal($stolenExtractorBag->getCrystal());

        $target->stolenExtractors->addMetal($stolenExtractorBag->getMetal());
        $target->stolenExtractors->addCrystal($stolenExtractorBag->getCrystal());
    }

    private function splitStolenExtractors(
        FleetCollection $attackerFleetCollection,
        UnitCollection $unitCollection,
        ExtractorBag $stolenExtractorBag,
        int $totalStealPotential,
    ): void {
        foreach ($attackerFleetCollection->getAll() as $fleet) {
            $fleetPotential = $fleet->units->countExtractorStealPotential($unitCollection);

            if ($fleetPotential <= 0) {
                continue;
            }

            $sharePercent = $fleetPotential / $totalStealPotential * 100;

            $fleet->stolenExtractors->addMetal((int) round($sharePercent / 100 * $stolenExtractorBag->getMetal()));
            $fleet->stolenExtractors->addCrystal((int) round($sharePercent / 100 * $stolenExtractorBag->getCrystal()));

            $this->addExtractorLosses($fleet, $unitCollection);
        }
    }

    private function addExtractorLosses(Fleet $fleet, UnitCollection $unitCollection): void
    {
        $undistributedExtractors = $fleet->stolenExtractors->getTotal();

        foreach ($unitCollection->getAll() as $unit) {
            if ($unit->extractorSteal === 0 || !$fleet->units->has($unit->unitId)) {
                continue;
            }

            if ($undistributedExtractors <= 0) {
                break;
            }

            $neededUnitsForLeftExtractors = (int) ceil($undistributedExtractors / $unit->extractorSteal);
            $numberOfUnitsInFleet = $fleet->units->get($unit->unitId);
            $reduceUnitNumberBy = $neededUnitsForLeftExtractors;

            if ($numberOfUnitsInFleet <= $neededUnitsForLeftExtractors) {
                $reduceUnitNumberBy = $numberOfUnitsInFleet;
            }

            $undistributedExtractors = $reduceUnitNumberBy * $unit->extractorSteal;

            $fleet->units->sub($unit->unitId, $reduceUnitNumberBy);
            $fleet->losses->add($unit->unitId, $reduceUnitNumberBy);
            $fleet->extractorLosses->add($unit->unitId, $reduceUnitNumberBy);
        }
    }
}
