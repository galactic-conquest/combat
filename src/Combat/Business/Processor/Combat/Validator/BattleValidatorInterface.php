<?php

declare(strict_types=1);

namespace GC\Combat\Business\Processor\Combat\Validator;

use GC\Combat\Transfer\Battle;
use GC\Combat\Transfer\BattleConfig;

interface BattleValidatorInterface
{
    public function ensureConsistentBattleData(Battle $battle, BattleConfig $battleConfig): void;
}
