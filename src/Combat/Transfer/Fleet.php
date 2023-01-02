<?php

declare(strict_types=1);

namespace GC\Combat\Transfer;

use GC\Combat\Business\Extractor\ExtractorBag;
use GC\Combat\Business\Resource\ResourceBag;

final class Fleet
{
    public function __construct(
        public readonly string $fleetId,
        public readonly string $name,
        public readonly FleetConfig $fleetConfig = new FleetConfig(),
        public readonly FleetUnitBag $units = new FleetUnitBag(),
        public readonly FleetUnitBag $losses = new FleetUnitBag(),
        public readonly FleetUnitBag $destroyed = new FleetUnitBag(),
        public readonly FleetUnitBag $carrierCapacityLosses = new FleetUnitBag(),
        public readonly FleetUnitBag $extractorLosses = new FleetUnitBag(),
        public readonly ResourceBag $cost = new ResourceBag(),
        public readonly ResourceBag $salvaged = new ResourceBag(),
        public readonly ExtractorBag $stolenExtractors = new ExtractorBag(),
    ) {
    }
}
