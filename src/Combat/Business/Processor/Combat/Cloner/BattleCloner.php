<?php

declare(strict_types=1);

namespace GC\Combat\Business\Processor\Combat\Cloner;

use GC\Combat\Transfer\Battle;
use GC\Combat\Transfer\Fleet;
use GC\Combat\Transfer\FleetCollection;
use GC\Combat\Transfer\FleetConfig;
use GC\Combat\Transfer\FleetUnitBag;
use GC\Combat\Transfer\Player;
use GC\Combat\Transfer\PlayerCollection;
use GC\Combat\Transfer\Target;
use GC\Combat\Business\Extractor\ExtractorBag;

final class BattleCloner implements BattleClonerInterface
{
    public function clone(Battle $battle): Battle
    {
        return new Battle(
            $this->cloneTarget($battle->target),
            $this->clonePlayerCollection($battle->attackers),
            $this->clonePlayerCollection($battle->defenders),
        );
    }

    private function clonePlayerCollection(PlayerCollection $playerCollection): PlayerCollection
    {
        $cloned = new PlayerCollection();

        foreach ($playerCollection->getAll() as $player) {
            $cloned->addPlayer($this->clonePlayer($player));
        }

        return $cloned;
    }

    private function clonePlayer(Player $player): Player
    {
        return new Player(
            $player->playerId,
            $player->name,
            $this->cloneFleetCollection($player->fleets),
            $player->galaxyNumber,
            $player->galaxyPosition
        );
    }

    private function cloneTarget(Target $target): Target
    {
        return new Target(
            $target->playerId,
            $target->name,
            $this->cloneFleetCollection($target->fleets),
            $this->cloneExtractorBag($target->extractors),
            $target->galaxyNumber,
            $target->galaxyPosition
        );
    }

    private function cloneExtractorBag(ExtractorBag $extractorBag): ExtractorBag
    {
        return new ExtractorBag(
            $extractorBag->getMetal(),
            $extractorBag->getCrystal()
        );
    }

    private function cloneFleetCollection(FleetCollection $fleetCollection): FleetCollection
    {
        $cloned = new FleetCollection();

        foreach ($fleetCollection->getAll() as $fleet) {
            $cloned->addFleet($this->cloneFleet($fleet));
        }

        return $cloned;
    }

    private function cloneFleet(Fleet $fleet): Fleet
    {
        return new Fleet(
            $fleet->fleetId,
            $fleet->name,
            $this->cloneFleetConfig($fleet->fleetConfig),
            $this->cloneFleetUnitBag($fleet->units)
        );
    }

    private function cloneFleetConfig(FleetConfig $fleetConfig): FleetConfig
    {
        return new FleetConfig(
            $fleetConfig->calculateCarrierCapacityLosses
        );
    }

    private function cloneFleetUnitBag(FleetUnitBag $fleetUnitBag): FleetUnitBag
    {
        return new FleetUnitBag(
            $fleetUnitBag->getAll()
        );
    }
}
