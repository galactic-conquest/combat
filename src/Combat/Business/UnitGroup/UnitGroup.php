<?php

declare(strict_types=1);

namespace GC\Combat\Business\UnitGroup;

enum UnitGroup: string
{
    case OFFENSE = 'offense';
    case DEFENSE = 'defense';
    case RECON = 'recon';

    public function isOffense(): bool
    {
        return $this === self::OFFENSE;
    }

    public function isDefense(): bool
    {
        return $this === self::DEFENSE;
    }

    public function isRecon(): bool
    {
        return $this === self::RECON;
    }
}
