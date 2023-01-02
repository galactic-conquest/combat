<?php

declare(strict_types=1);

namespace GC\Combat\Business\Processor\Combat;

use GC\Combat\Transfer\CombatRequest;
use GC\Combat\Transfer\CombatResponse;

interface CombatProcessorInterface
{
    public function combat(CombatRequest $combatRequest): CombatResponse;
}
