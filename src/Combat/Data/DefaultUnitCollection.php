<?php

declare(strict_types=1);

namespace GC\Combat\Data;

use GC\Combat\Transfer\UnitCollection;

final class DefaultUnitCollection extends UnitData
{
    public function __invoke(): UnitCollection
    {
        return new UnitCollection(
            ...[
                $this->getLeo(),
                $this->getAquilae(),
                $this->getFornax(),
                $this->getDraco(),
                $this->getGoron(),
                $this->getPentalin(),
                $this->getZenit(),
                $this->getCleptor(),
                $this->getCancri(),
                $this->getRubium(),
                $this->getPulsar(),
                $this->getCoon(),
                $this->getCenturion(),
                $this->getHorus(),
                $this->getScanRelais(),
                $this->getScanBlocker(),
            ]
        );
    }
}
