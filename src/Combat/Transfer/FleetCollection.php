<?php

declare(strict_types=1);

namespace GC\Combat\Transfer;

use GC\Combat\Business\Resource\ResourceBag;
use GC\Combat\Exception\FleetException;

final class FleetCollection
{
    /**
     * @var array<string,Fleet>
     */
    private array $fleets = [];

    public function __construct(Fleet ...$fleets)
    {
        $this->addFleets(...$fleets);
    }

    /**
     * @return array<string,Fleet>
     */
    public function getAll(): array
    {
        return $this->fleets;
    }

    public function addFleet(Fleet $fleet): FleetCollection
    {
        $this->fleets[$fleet->fleetId] = $fleet;

        return $this;
    }

    public function addFleets(Fleet ...$fleets): FleetCollection
    {
        foreach ($fleets as $fleet) {
            $this->addFleet($fleet);
        }

        return $this;
    }

    public function getFleetById(string $fleetId): Fleet
    {
        return $this->fleets[$fleetId] ?? throw FleetException::forMissingFleet($fleetId);
    }

    public function getTotalUnits(): FleetUnitBag
    {
        $fleetBag = new FleetUnitBag();

        foreach ($this->fleets as $fleet) {
            $fleetBag->addBag($fleet->units);
        }

        return $fleetBag;
    }

    public function calculateCostDestroyed(UnitCollection $unitCollection): ResourceBag
    {
        $cost = new ResourceBag();

        foreach ($this->fleets as $fleet) {
            $cost->add($fleet->destroyed->calculateCost($unitCollection));
        }

        return $cost;
    }

    public function calculateCostExtractorLosses(UnitCollection $unitCollection): ResourceBag
    {
        $cost = new ResourceBag();

        foreach ($this->fleets as $fleet) {
            $cost->add($fleet->extractorLosses->calculateCost($unitCollection));
        }

        return $cost;
    }

    public function calculateCostCarrierCapacityLosses(UnitCollection $unitCollection): ResourceBag
    {
        $cost = new ResourceBag();

        foreach ($this->fleets as $fleet) {
            $cost->add($fleet->carrierCapacityLosses->calculateCost($unitCollection));
        }

        return $cost;
    }

    public function countExtractorStealPotential(UnitCollection $unitCollection): int
    {
        $potential = 0;

        foreach ($this->fleets as $fleet) {
            $potential += $fleet->units->countExtractorStealPotential($unitCollection);
        }

        return $potential;
    }

    public function countExtractorGuardPotential(UnitCollection $unitCollection): int
    {
        $potential = 0;

        foreach ($this->fleets as $fleet) {
            $potential += $fleet->units->countExtractorGuardPotential($unitCollection);
        }

        return $potential;
    }
}
