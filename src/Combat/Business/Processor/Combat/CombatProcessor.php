<?php

declare(strict_types=1);

namespace GC\Combat\Business\Processor\Combat;

use GC\Combat\Business\Processor\Combat\Cloner\BattleClonerInterface;
use GC\Combat\Business\Processor\Combat\Plugin\CombatPluginInterface;
use GC\Combat\Business\Processor\Combat\Validator\BattleValidatorInterface;
use GC\Combat\Transfer\CombatRequest;
use GC\Combat\Transfer\CombatResponse;

final class CombatProcessor implements CombatProcessorInterface
{
    /**
     * @param array<CombatPluginInterface> $combatPlugins
     */
    public function __construct(
        public readonly array $combatPlugins,
        public readonly BattleClonerInterface $battleCloner,
        public readonly BattleValidatorInterface $battleValidator,
    ) {
    }

    public function combat(CombatRequest $combatRequest): CombatResponse
    {
        $this->battleValidator->ensureConsistentBattleData(
            $combatRequest->battle,
            $combatRequest->config,
        );

        $combatResponse = new CombatResponse(
            $combatRequest->battle,
            $this->battleCloner->clone($combatRequest->battle),
            $combatRequest->config,
        );

        foreach ($this->combatPlugins as $combatPlugin) {
            if ($combatPlugin->shouldExecute($combatResponse)) {
                $combatResponse = $combatPlugin->execute($combatResponse);
            }
        }

        return $combatResponse;
    }
}
