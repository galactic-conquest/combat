<?php

declare(strict_types=1);

namespace GC\Combat\Transfer;

use GC\Combat\Business\Extractor\ExtractorBag;
use GC\Combat\Business\Resource\ResourceBag;

final class Target
{
    public function __construct(
        public readonly string $playerId,
        public readonly string $name,
        public readonly FleetCollection $fleets,
        public readonly ExtractorBag $extractors = new ExtractorBag(),
        public readonly ?int $galaxyNumber = null,
        public readonly ?int $galaxyPosition = null,
        public readonly ExtractorBag $stolenExtractors = new ExtractorBag(),
        public readonly ResourceBag $salvaged = new ResourceBag(),
    ) {
    }
}
