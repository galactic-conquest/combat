<?php

declare(strict_types=1);

namespace GC\Combat\Business;

use GC\Combat\Transfer\CombatRequest;
use GC\Combat\Transfer\CombatResponse;
use GC\Combat\Transfer\FormatRequest;

class CombatFacade implements CombatFacadeInterface
{
    public function __construct(
        private readonly CombatBusinessFactory $factory
    ) {
    }

    /**
     * Specification
     * - Calculates a combat
     */
    public function combat(CombatRequest $combatRequest): CombatResponse
    {
        return $this->factory
            ->getCombatProcessor()
            ->combat($combatRequest);
    }

    /**
     * Specification
     * - Formats the combat response to json
     */
    public function formatJson(FormatRequest $request): string
    {
        return $this->factory
            ->getFormatterProcessor()
            ->formatJson($request);
    }

    /**
     * Specification
     * - Formats the combat response to array
     *
     * @return array<string,mixed>
     */
    public function formatArray(FormatRequest $request): array
    {
        return $this->factory
            ->getFormatterProcessor()
            ->formatArray($request);
    }
}
