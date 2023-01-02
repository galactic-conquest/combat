<?php

declare(strict_types=1);

namespace GC\Combat\Transfer;

use GC\Combat\Exception\PlayerException;

use function count;

final class PlayerCollection
{
    /**
     * @var array<Player>
     */
    private array $players = [];

    public function __construct(Player ...$players)
    {
        $this->addPlayers(...$players);
    }

    /**
     * @return array<Player>
     */
    public function getAll(): array
    {
        return $this->players;
    }

    public function hasPlayers(): bool
    {
        return count($this->players) > 0;
    }

    public function addPlayers(Player ...$players): PlayerCollection
    {
        foreach ($players as $player) {
            $this->addPlayer($player);
        }

        return $this;
    }

    public function addPlayer(Player $player): PlayerCollection
    {
        $this->players[$player->playerId] = $player;

        return $this;
    }

    public function getPlayerById(string $playerId): Player
    {
        return $this->players[$playerId] ?? throw PlayerException::forMissingPlayer($playerId);
    }

    public function getFleetCollection(): FleetCollection
    {
        $collection = new FleetCollection();

        foreach ($this->players as $player) {
            $collection->addFleets(...$player->fleets->getAll());
        }

        return $collection;
    }
}
