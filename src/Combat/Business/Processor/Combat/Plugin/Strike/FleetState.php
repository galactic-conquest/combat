<?php

declare(strict_types=1);

namespace GC\Combat\Business\Processor\Combat\Plugin\Strike;

use GC\Combat\Transfer\FleetUnitBag;

final class FleetState
{
    public readonly FleetUnitPrecisionBag $after;

    public function __construct(
        public readonly FleetUnitBag $before,
        public readonly FleetUnitPrecisionBag $losses = new FleetUnitPrecisionBag(),
        public readonly FleetUnitPrecisionBag $destroyed = new FleetUnitPrecisionBag(),
    ) {
        $this->after = new FleetUnitPrecisionBag($this->before->getAll());
    }
}
