<?php

declare(strict_types=1);

namespace GC\Combat\Business\Resource;

final class ResourceBag
{
    public function __construct(
        private int $metal = 0,
        private int $crystal = 0,
    ) {
    }

    public function getMetal(): int
    {
        return $this->metal;
    }

    public function addMetal(int $number): void
    {
        $this->metal += $number;
    }

    public function subMetal(int $number): void
    {
        $this->metal -= $number;
    }

    public function getCrystal(): int
    {
        return $this->crystal;
    }

    public function addCrystal(int $number): void
    {
        $this->crystal += $number;
    }

    public function subCrystal(int $number): void
    {
        $this->crystal -= $number;
    }

    public function isEmpty(): bool
    {
        return $this->getTotal() <= 0;
    }

    public function getTotal(): int
    {
        return $this->metal + $this->crystal;
    }

    public function add(ResourceBag $resourceBag): void
    {
        $this->addMetal($resourceBag->getMetal());
        $this->addCrystal($resourceBag->getCrystal());
    }

    public function sub(ResourceBag $resourceBag): void
    {
        $this->subMetal($resourceBag->getMetal());
        $this->subCrystal($resourceBag->getCrystal());
    }
}
