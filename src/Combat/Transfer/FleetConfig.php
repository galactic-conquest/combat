<?php

declare(strict_types=1);

namespace GC\Combat\Transfer;

final class FleetConfig
{
    public function __construct(
        public readonly bool $calculateCarrierCapacityLosses = false,
    ) {
    }
}
