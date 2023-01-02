<?php

declare(strict_types=1);

namespace GC\Combat\Business\Processor\Combat\Cloner;

use GC\Combat\Transfer\Battle;

interface BattleClonerInterface
{
    public function clone(Battle $battle): Battle;
}
