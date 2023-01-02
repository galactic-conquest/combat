<?php

declare(strict_types=1);

namespace GC\Combat\Business;

use GC\Combat\Business\Processor\Combat\Cloner\BattleCloner;
use GC\Combat\Business\Processor\Combat\CombatProcessor;
use GC\Combat\Business\Processor\Combat\CombatProcessorInterface;
use GC\Combat\Business\Processor\Combat\Plugin\CarrierCombatPlugin;
use GC\Combat\Business\Processor\Combat\Plugin\CombatPluginInterface;
use GC\Combat\Business\Processor\Combat\Plugin\CostCombatPlugin;
use GC\Combat\Business\Processor\Combat\Plugin\ExtractorCombatPlugin;
use GC\Combat\Business\Processor\Combat\Plugin\SalvageCombatPlugin;
use GC\Combat\Business\Processor\Combat\Plugin\StrikeCombatPlugin;
use GC\Combat\Business\Processor\Combat\Validator\BattleValidator;
use GC\Combat\Business\Processor\ResponseFormatter\FormatterProcessor;
use GC\Combat\Business\Processor\ResponseFormatter\FormatterProcessorInterface;

class CombatBusinessFactory
{
    public function getCombatProcessor(): CombatProcessorInterface
    {
        return new CombatProcessor(
            $this->getCombatPlugins(),
            new BattleCloner(),
            new BattleValidator(),
        );
    }

    /**
     * @return array<CombatPluginInterface>
     */
    private function getCombatPlugins(): array
    {
        return [
            new StrikeCombatPlugin(),
            new ExtractorCombatPlugin(),
            new CarrierCombatPlugin(),
            new CostCombatPlugin(),
            new SalvageCombatPlugin(),
        ];
    }

    public function getFormatterProcessor(): FormatterProcessorInterface
    {
        return new FormatterProcessor();
    }
}
