<?php

declare(strict_types=1);

namespace GC\Combat\Transfer;

use GC\Combat\Business\Resource\ResourceBag;

use function array_filter;

final class FleetUnitBag
{
    /**
     * @param array<string,int> $units
     */
    public function __construct(
        private array $units = [],
    ) {
    }

    /**
     * @return array<string,int>
     */
    public function getAll(): array
    {
        return array_filter($this->units);
    }

    public function addBag(FleetUnitBag $units): void
    {
        foreach ($units->getAll() as $unitId => $number) {
            $this->add($unitId, $number);
        }
    }

    public function add(string $unitId, int $number): void
    {
        if ($number <= 0) {
            return;
        }

        $this->units[$unitId] = $this->units[$unitId] ?? 0;
        $this->units[$unitId] += $number;
    }

    public function sub(string $unitId, int $number): void
    {
        $this->units[$unitId] = $this->units[$unitId] ?? 0;
        $this->units[$unitId] -= $number;

        if ($this->units[$unitId] < 0) {
            unset($this->units[$unitId]);
        }
    }

    public function has(string $unitId): bool
    {
        return ($this->units[$unitId] ?? 0) > 0;
    }

    public function get(string $unitId): int
    {
        return $this->units[$unitId] ?? 0;
    }

    public function calculateCost(UnitCollection $unitCollection): ResourceBag
    {
        $cost = new ResourceBag();

        foreach ($this->units as $unitId => $number) {
            $cost->addMetal($number * $unitCollection->getById($unitId)->cost->getMetal());
            $cost->addCrystal($number * $unitCollection->getById($unitId)->cost->getCrystal());
        }

        return $cost;
    }

    public function countCarrierConsumption(UnitCollection $unitCollection): int
    {
        $consumption = 0;

        foreach ($this->units as $unitId => $number) {
            $unit = $unitCollection->getById($unitId);
            if ($unit->carrierConsumption > 0) {
                $consumption += $number * $unit->carrierConsumption;
            }
        }

        return $consumption;
    }

    public function countCarrierCapacity(UnitCollection $unitCollection): int
    {
        $capacity = 0;

        foreach ($this->units as $unitId => $number) {
            $unit = $unitCollection->getById($unitId);
            if ($unit->carrierCapacity > 0) {
                $capacity += $number * $unit->carrierCapacity;
            }
        }

        return $capacity;
    }

    public function countExtractorStealPotential(UnitCollection $unitCollection): int
    {
        $potential = 0;

        foreach ($this->units as $unitId => $number) {
            $unit = $unitCollection->getById($unitId);
            if ($unit->extractorSteal > 0) {
                $potential += $number * $unit->extractorSteal;
            }
        }

        return $potential;
    }

    public function countExtractorGuardPotential(UnitCollection $unitCollection): int
    {
        $potential = 0;

        foreach ($this->units as $unitId => $number) {
            $unit = $unitCollection->getById($unitId);
            if ($unit->extractorGuard > 0) {
                $potential += $number * $unit->extractorGuard;
            }
        }

        return $potential;
    }
}
