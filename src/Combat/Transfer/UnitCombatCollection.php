<?php

declare(strict_types=1);

namespace GC\Combat\Transfer;

use function count;
use function min;

class UnitCombatCollection
{
    /**
     * @var array<UnitCombat>
     */
    private array $unitCombats = [];

    public function __construct(UnitCombat ...$unitCombats)
    {
        $this->addMultiple(...$unitCombats);
    }

    /**
     * @return array<UnitCombat>
     */
    public function getAll(): array
    {
        return $this->unitCombats;
    }

    public function isEmpty(): bool
    {
        return count($this->unitCombats) === 0;
    }

    public function addMultiple(UnitCombat ...$unitCombats): UnitCombatCollection
    {
        foreach ($unitCombats as $unitCombat) {
            $this->add($unitCombat);
        }

        return $this;
    }

    public function add(UnitCombat $unitCombat): UnitCombatCollection
    {
        $this->unitCombats[] = $unitCombat;

        return $this;
    }

    /**
     * @return array<UnitCombat>
     */
    public function getByMode(int $mode): array
    {
        $combats = [];

        foreach ($this->unitCombats as $combat) {
            if ($combat->mode === $mode) {
                $combats[] = $combat;
            }
        }

        return $combats;
    }

    public function getMinRatio(): ?float
    {
        $ratios = [];

        foreach ($this->unitCombats as $combat) {
            $ratios[] = $combat->ratio;
        }

        if (count($ratios) === 0) {
            return null;
        }

        return min($ratios);
    }
}
