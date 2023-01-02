<?php

declare(strict_types=1);

namespace GC\Combat\Business;

use GC\Combat\Transfer\CombatRequest;
use GC\Combat\Transfer\CombatResponse;
use GC\Combat\Transfer\FormatRequest;

interface CombatFacadeInterface
{
    /**
     * Specification
     * - Calculates a combat
     */
    public function combat(CombatRequest $combatRequest): CombatResponse;

    /**
     * Specification
     * - Formats the combat response to json
     */
    public function formatJson(FormatRequest $request): string;

    /**
     * Specification
     * - Formats the combat response to array
     *
     * @return array<string,mixed>
     */
    public function formatArray(FormatRequest $request): array;
}
