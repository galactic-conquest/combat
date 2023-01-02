<?php

declare(strict_types=1);

namespace GC\Combat\Business\Processor\Combat\Plugin;

use GC\Combat\Transfer\CombatResponse;

interface CombatPluginInterface
{
    public function shouldExecute(CombatResponse $combatResponse): bool;

    public function execute(CombatResponse $combatResponse): CombatResponse;
}
