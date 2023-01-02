<?php

declare(strict_types=1);

namespace GC\Combat\Transfer;

use GC\Combat\Business\Resource\ResourceBag;

final class Battle
{
    public function __construct(
        public readonly Target $target,
        public readonly PlayerCollection $attackers,
        public readonly PlayerCollection $defenders,
    ) {
    }

    public function getAllAttackerFleets(): FleetCollection
    {
        return $this->attackers->getFleetCollection();
    }

    public function getAllDefenderAndTargetFleets(): FleetCollection
    {
        return $this->defenders
            ->getFleetCollection()
            ->addFleets(...$this->target->fleets->getAll());
    }

    public function getAllAttackerAndDefenderFleets(): FleetCollection
    {
        return $this->getAllAttackerFleets()
            ->addFleets(...$this->defenders->getFleetCollection()->getAll());
    }

    public function getAllFleets(): FleetCollection
    {
        return $this->getAllAttackerFleets()
            ->addFleets(...$this->getAllDefenderAndTargetFleets()->getAll());
    }

    public function getTotalCostForAllFleets(): ResourceBag
    {
        $resourceBag = new ResourceBag();

        foreach ($this->getAllFleets()->getAll() as $fleet) {
            $resourceBag->addMetal($fleet->cost->getMetal());
            $resourceBag->addCrystal($fleet->cost->getCrystal());
        }

        return $resourceBag;
    }
}
