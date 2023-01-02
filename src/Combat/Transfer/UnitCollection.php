<?php

declare(strict_types=1);

namespace GC\Combat\Transfer;

use GC\Combat\Exception\UnitException;

class UnitCollection
{
    /**
     * @var array<Unit>
     */
    private array $units = [];

    public function __construct(Unit ...$units)
    {
        $this->addMultiple(...$units);
    }

    /**
     * @return array<Unit>
     */
    public function getAll(): array
    {
        return $this->units;
    }

    public function addMultiple(Unit ...$units): UnitCollection
    {
        foreach ($units as $unit) {
            $this->add($unit);
        }

        return $this;
    }

    public function add(Unit $unit): UnitCollection
    {
        $this->units[$unit->unitId] = $unit;

        return $this;
    }

    public function getById(string $unitId): Unit
    {
        return $this->units[$unitId] ?? throw UnitException::forUnitNotFound($unitId);
    }

    public function has(string $unitId): bool
    {
        return array_key_exists($unitId, $this->units);
    }
}
