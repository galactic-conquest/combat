<?php

declare(strict_types=1);

namespace GC\Combat\Business\Processor\Combat\Plugin\Strike;

final class FleetUnitPrecisionBag
{
    /**
     * @var array<string,float> $units
     */
    private array $units = [];

    /**
     * @param array<string,int|float> $units
     */
    public function __construct(array $units = [])
    {
        foreach ($units as $unitId => $number) {
            $this->add($unitId, $number);
        }
    }

    public function add(string $unitId, int|float $number): void
    {
        $this->units[$unitId] = $this->units[$unitId] ?? 0.0;
        $this->units[$unitId] += (float) $number;
    }

    public function sub(string $unitId, int|float $number): void
    {
        $this->units[$unitId] = $this->units[$unitId] ?? 0.0;
        $this->units[$unitId] -= (float) $number;

        if ($this->units[$unitId] < 0.0) {
            $this->units[$unitId] = 0.0;
        }
    }

    public function get(string $unitId): float
    {
        return $this->units[$unitId] ?? 0.0;
    }
}
