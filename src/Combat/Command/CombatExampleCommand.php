<?php

declare(strict_types=1);

namespace GC\Combat\Command;

use GC\Combat\Business\CombatFacadeInterface;
use GC\Combat\Business\Extractor\ExtractorBag;
use GC\Combat\Data\DefaultUnitCollection;
use GC\Combat\Data\UnitDataInterface;
use GC\Combat\Transfer\Battle;
use GC\Combat\Transfer\BattleConfig;
use GC\Combat\Transfer\CombatRequest;
use GC\Combat\Transfer\Fleet;
use GC\Combat\Transfer\FleetCollection;
use GC\Combat\Transfer\FleetConfig;
use GC\Combat\Transfer\FleetUnitBag;
use GC\Combat\Transfer\FormatRequest;
use GC\Combat\Transfer\Player;
use GC\Combat\Transfer\PlayerCollection;
use GC\Combat\Transfer\Target;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CombatExampleCommand extends Command
{
    private const NAME = 'combat:example';

    public function __construct(
        private readonly CombatFacadeInterface $combatFacade
    ) {
        parent::__construct(self::NAME);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $combatResponse = $this->combatFacade->combat(
            new CombatRequest(
                new Battle(
                    $this->createTarget(),
                    $this->createAttackers(),
                    $this->createDefenders(),
                ),
                new BattleConfig(
                    // BattleMode values
                    // 0 => regular battle
                    // 1 => pre strike, 1 tick before regular battle
                    // 2 => pre strike, 2 tick before regular battle
                    // 3 => pre strike, 3 tick before regular battle etc...
                    BattleConfig::MODE_BATTLE,
                    (new DefaultUnitCollection())(), // unit config
                    BattleConfig::DEFAULT_SALVAGE_TARGET_RATIO, // 40 percent
                    BattleConfig::DEFAULT_SALVAGE_DEFENDER_RATIO, // 20 percent
                    BattleConfig::DEFAULT_EXTRACTOR_STEAL_RATIO, // 10 percent
                ),
            )
        );

        $jsonBattleResult = $this->combatFacade->formatJson(
            new FormatRequest(
                $combatResponse,
                true,
                false,
                true,
            )
        );

        $output->writeln($jsonBattleResult);

        return 0;
    }

    private function createTarget(): Target
    {
        $fleets = [
            new Fleet(
                $this->getRandomId(),
                'Orbit',
                new FleetConfig(),
                new FleetUnitBag(
                    [
                        UnitDataInterface::LEO_ID => 100,
                        UnitDataInterface::AQUILAE_ID => 100,
                        UnitDataInterface::FORNAX_ID => 100,
                    ]
                )
            ),
            new Fleet(
                $this->getRandomId(),
                '1. Fleet',
                new FleetConfig(),
                new FleetUnitBag(
                    [
                        UnitDataInterface::LEO_ID => 100,
                        UnitDataInterface::AQUILAE_ID => 100,
                        UnitDataInterface::FORNAX_ID => 100,
                        UnitDataInterface::DRACO_ID => 100,
                        UnitDataInterface::GORON_ID => 100,
                        UnitDataInterface::PENTALIN_ID => 100,
                        UnitDataInterface::ZENIT_ID => 100,
                        UnitDataInterface::CLEPTOR_ID => 100,
                        UnitDataInterface::CANCRI_ID => 100,
                    ]
                )
            ),
            new Fleet(
                $this->getRandomId(),
                'Defense',
                new FleetConfig(),
                new FleetUnitBag(
                    [
                        UnitDataInterface::RUBIUM_ID => 100,
                        UnitDataInterface::PULSAR_ID => 100,
                        UnitDataInterface::COON_ID => 100,
                        UnitDataInterface::CENTURION_ID => 100,
                        UnitDataInterface::HORUS_ID => 100,
                    ]
                )
            ),
        ];

        return new Target(
            'targetId',
            'TargetName',
            new FleetCollection(...$fleets),
            new ExtractorBag(1000, 1000),
        );
    }

    private function createAttackers(): PlayerCollection
    {
        return new PlayerCollection(
            ...
            [
                new Player(
                    $this->getRandomId(),
                    'PlayerOne',
                    new FleetCollection(
                        ...
                        [
                            new Fleet(
                                $this->getRandomId(),
                                '1. Fleet',
                                new FleetConfig(true),
                                new FleetUnitBag(
                                    [
                                        UnitDataInterface::PENTALIN_ID => 30,
                                    ]
                                ),
                            ),
                            new Fleet(
                                $this->getRandomId(),
                                '2. Fleet',
                                new FleetConfig(true),
                                new FleetUnitBag(
                                    [
                                        UnitDataInterface::LEO_ID => 5000,
                                        UnitDataInterface::AQUILAE_ID => 500,
                                        UnitDataInterface::FORNAX_ID => 200,
                                        UnitDataInterface::DRACO_ID => 100,
                                        UnitDataInterface::ZENIT_ID => 50,
                                        UnitDataInterface::CLEPTOR_ID => 10000,
                                    ]
                                ),
                            ),
                        ]
                    )
                ),
                new Player(
                    $this->getRandomId(),
                    'PlayerTwo',
                    new FleetCollection(
                        ...
                        [
                            new Fleet(
                                $this->getRandomId(),
                                '1. Fleet',
                                new FleetConfig(true),
                                new FleetUnitBag(
                                    [
                                        UnitDataInterface::DRACO_ID => 50,
                                        UnitDataInterface::GORON_ID => 53,
                                        UnitDataInterface::ZENIT_ID => 100,
                                        UnitDataInterface::CLEPTOR_ID => 5000,
                                    ]
                                ),
                            ),
                        ]
                    )
                ),
           ]
        );
    }

    private function createDefenders(): PlayerCollection
    {
        return new PlayerCollection(
            ...
            [
                new Player(
                    $this->getRandomId(),
                    'PlayerThree',
                    new FleetCollection(
                        ...
                        [
                            new Fleet(
                                $this->getRandomId(),
                                '1. Fleet',
                                new FleetConfig(true),
                                new FleetUnitBag(
                                    [
                                        UnitDataInterface::ZENIT_ID => 50,
                                        UnitDataInterface::CANCRI_ID => 10000,
                                    ]
                                ),
                            ),
                        ]
                    )
                ),
                new Player(
                    $this->getRandomId(),
                    'PlayerFour',
                    new FleetCollection(
                        ...
                        [
                            new Fleet(
                                $this->getRandomId(),
                                '1. Fleet',
                                new FleetConfig(true),
                                new FleetUnitBag(
                                    [
                                        UnitDataInterface::PENTALIN_ID => 40,
                                        UnitDataInterface::CANCRI_ID => 2000,
                                    ]
                                ),
                            ),
                        ]
                    )
                ),
            ]
        );
    }

    private function getRandomId(): string
    {
        return uniqid('id', true);
    }
}
