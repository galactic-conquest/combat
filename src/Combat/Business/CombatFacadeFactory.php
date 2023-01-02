<?php

declare(strict_types=1);

namespace GC\Combat\Business;

class CombatFacadeFactory
{
    public function __invoke(): CombatFacadeInterface
    {
        return new CombatFacade(
            new CombatBusinessFactory(),
        );
    }
}
