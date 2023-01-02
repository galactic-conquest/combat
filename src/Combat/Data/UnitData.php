<?php

declare(strict_types=1);

namespace GC\Combat\Data;

use GC\Combat\Business\UnitGroup\UnitGroup;
use GC\Combat\Transfer\Unit;
use GC\Combat\Transfer\UnitCombat;
use GC\Combat\Transfer\UnitCombatCollection;
use GC\Combat\Business\Resource\ResourceBag;

abstract class UnitData implements UnitDataInterface
{
    protected function getLeo(): Unit
    {
        return new Unit(
            ...[
                'unitId' => UnitDataInterface::LEO_ID,
                'name' => 'Leo',
                'codeName' => 'JA',
                'unitClass' => 'Jäger',
                'description' => 'Jäger',
                'group' => UnitGroup::OFFENSE,
                'cost' => new ResourceBag(4000, 6000),
                'ticksCost' => 12,
                'carrierCapacity' => 0,
                'carrierConsumption' => 1,
                'extractorSteal' => 0,
                'extractorGuard' => 0,
                'scanRelaisFactor' => 0,
                'scanBlockerFactor' => 0,
                'cargo' => 0,
                'combats' => new UnitCombatCollection(
                    ...[
                        new UnitCombat(UnitDataInterface::LEO_ID, UnitDataInterface::COON_ID, 0.35, 0.0246, 0),
                        new UnitCombat(UnitDataInterface::LEO_ID, UnitDataInterface::AQUILAE_ID, 0.30, 0.392, 0),
                        new UnitCombat(UnitDataInterface::LEO_ID, UnitDataInterface::GORON_ID, 0.35, 0.0263, 0),
                    ]
                ),
            ],
        );
    }

    protected function getAquilae(): Unit
    {
        return new Unit(
            ...[
                'unitId' => UnitDataInterface::AQUILAE_ID,
                'name' => 'Aquilae',
                'codeName' => 'BO',
                'unitClass' => 'Bomber',
                'description' => 'Bomber',
                'group' => UnitGroup::OFFENSE,
                'cost' => new ResourceBag(2000, 8000),
                'ticksCost' => 16,
                'carrierCapacity' => 0,
                'carrierConsumption' => 1,
                'extractorSteal' => 0,
                'extractorGuard' => 0,
                'scanRelaisFactor' => 0,
                'scanBlockerFactor' => 0,
                'cargo' => 0,
                'combats' => new UnitCombatCollection(
                    ...[
                        new UnitCombat(UnitDataInterface::AQUILAE_ID, UnitDataInterface::CENTURION_ID, 0.35, 0.0080, 0),
                        new UnitCombat(UnitDataInterface::AQUILAE_ID, UnitDataInterface::PENTALIN_ID, 0.35, 0.0100, 0),
                        new UnitCombat(UnitDataInterface::AQUILAE_ID, UnitDataInterface::ZENIT_ID, 0.30, 0.0075, 0),
                    ]
                ),
            ],
        );
    }

    protected function getFornax(): Unit
    {
        return new Unit(
            ...[
                'unitId' => UnitDataInterface::FORNAX_ID,
                'name' => 'Fornax',
                'codeName' => 'FR',
                'unitClass' => 'Fregatte',
                'description' => 'Fregatte',
                'group' => UnitGroup::OFFENSE,
                'cost' => new ResourceBag(15000, 7500),
                'ticksCost' => 32,
                'carrierCapacity' => 0,
                'carrierConsumption' => 0,
                'extractorSteal' => 0,
                'extractorGuard' => 0,
                'scanRelaisFactor' => 0,
                'scanBlockerFactor' => 0,
                'cargo' => 0,
                'combats' => new UnitCombatCollection(
                    ...[
                        new UnitCombat(UnitDataInterface::FORNAX_ID, UnitDataInterface::HORUS_ID, 0.6, 4.5, 0),
                        new UnitCombat(UnitDataInterface::FORNAX_ID, UnitDataInterface::LEO_ID, 0.4, 0.85, 0),
                    ]
                ),
            ],
        );
    }

    protected function getDraco(): Unit
    {
        return new Unit(
            ...[
                'unitId' => UnitDataInterface::DRACO_ID,
                'name' => 'Draco',
                'codeName' => 'ZE',
                'unitClass' => 'Zerstörer',
                'description' => 'Zerstörer',
                'group' => UnitGroup::OFFENSE,
                'cost' => new ResourceBag(40000, 30000),
                'ticksCost' => 56,
                'carrierCapacity' => 0,
                'carrierConsumption' => 0,
                'extractorSteal' => 0,
                'extractorGuard' => 0,
                'scanRelaisFactor' => 0,
                'scanBlockerFactor' => 0,
                'cargo' => 0,
                'combats' => new UnitCombatCollection(
                    ...[
                        new UnitCombat(UnitDataInterface::DRACO_ID, UnitDataInterface::RUBIUM_ID, 0.6, 3.5, 0),
                        new UnitCombat(UnitDataInterface::DRACO_ID, UnitDataInterface::FORNAX_ID, 0.4, 1.2444, 0),
                    ]
                ),
            ],
        );
    }

    protected function getGoron(): Unit
    {
        return new Unit(
            ...[
                'unitId' => UnitDataInterface::GORON_ID,
                'name' => 'Goron',
                'codeName' => 'KR',
                'unitClass' => 'Kreuzer',
                'description' => 'Kreuzer',
                'group' => UnitGroup::OFFENSE,
                'cost' => new ResourceBag(65000, 85000),
                'ticksCost' => 112,
                'carrierCapacity' => 0,
                'carrierConsumption' => 0,
                'extractorSteal' => 0,
                'extractorGuard' => 0,
                'scanRelaisFactor' => 0,
                'scanBlockerFactor' => 0,
                'cargo' => 0,
                'combats' => new UnitCombatCollection(
                    ...[
                        new UnitCombat(UnitDataInterface::GORON_ID, UnitDataInterface::PULSAR_ID, 0.35, 2.0, 0),
                        new UnitCombat(UnitDataInterface::GORON_ID, UnitDataInterface::DRACO_ID, 0.30, 0.8571, 0),
                        new UnitCombat(UnitDataInterface::GORON_ID, UnitDataInterface::CANCRI_ID, 0.35, 10.0, 0),
                    ]
                ),
            ],
        );
    }

    protected function getPentalin(): Unit
    {
        return new Unit(
            ...[
                'unitId' => UnitDataInterface::PENTALIN_ID,
                'name' => 'Pentalin',
                'codeName' => 'SS',
                'unitClass' => 'Schlachtschiff',
                'description' => 'Schlachtschiff',
                'group' => UnitGroup::OFFENSE,
                'cost' => new ResourceBag(250000, 150000),
                'ticksCost' => 192,
                'carrierCapacity' => 0,
                'carrierConsumption' => 0,
                'extractorSteal' => 0,
                'extractorGuard' => 0,
                'scanRelaisFactor' => 0,
                'scanBlockerFactor' => 0,
                'cargo' => 0,
                'combats' => new UnitCombatCollection(
                    ...[
                        new UnitCombat(UnitDataInterface::PENTALIN_ID, UnitDataInterface::COON_ID, 0.2, 1.0, 0),
                        new UnitCombat(UnitDataInterface::PENTALIN_ID, UnitDataInterface::GORON_ID, 0.2, 1.0666, 0),
                        new UnitCombat(UnitDataInterface::PENTALIN_ID, UnitDataInterface::PENTALIN_ID, 0.2, 0.4, 0),
                        new UnitCombat(UnitDataInterface::PENTALIN_ID, UnitDataInterface::ZENIT_ID, 0.2, 0.3019, 0),
                        new UnitCombat(UnitDataInterface::PENTALIN_ID, UnitDataInterface::CANCRI_ID, 0.2, 26.6667, 0),
                    ]
                ),
            ],
        );
    }

    protected function getZenit(): Unit
    {
        return new Unit(
            ...[
                'unitId' => UnitDataInterface::ZENIT_ID,
                'name' => 'Zenit',
                'codeName' => 'TR',
                'unitClass' => 'Träger',
                'description' => 'Träger',
                'group' => UnitGroup::OFFENSE,
                'cost' => new ResourceBag(200000, 50000),
                'ticksCost' => 192,
                'carrierCapacity' => 100,
                'carrierConsumption' => 0,
                'extractorSteal' => 0,
                'extractorGuard' => 0,
                'scanRelaisFactor' => 0,
                'scanBlockerFactor' => 0,
                'cargo' => 0,
                'combats' => new UnitCombatCollection(
                    ...[
                        new UnitCombat(UnitDataInterface::ZENIT_ID, UnitDataInterface::CLEPTOR_ID, 0.5, 25.0, 0),
                        new UnitCombat(UnitDataInterface::ZENIT_ID, UnitDataInterface::CANCRI_ID, 0.5, 14.0, 0),
                    ]
                ),
            ],
        );
    }

    protected function getCleptor(): Unit
    {
        return new Unit(
            ...[
                'unitId' => UnitDataInterface::CLEPTOR_ID,
                'name' => 'Cleptor',
                'codeName' => 'CL',
                'unitClass' => 'Kaperschiff',
                'description' => 'Kaperschiff',
                'group' => UnitGroup::OFFENSE,
                'cost' => new ResourceBag(1500, 1000),
                'ticksCost' => 32,
                'carrierCapacity' => 0,
                'carrierConsumption' => 0,
                'extractorSteal' => 1,
                'extractorGuard' => 0,
                'scanRelaisFactor' => 0,
                'scanBlockerFactor' => 0,
                'cargo' => 0,
                'combats' => new UnitCombatCollection(),
            ],
        );
    }

    protected function getCancri(): Unit
    {
        return new Unit(
            ...[
                'unitId' => UnitDataInterface::CANCRI_ID,
                'name' => 'Cancri',
                'codeName' => 'CA',
                'unitClass' => 'Schutzschiff',
                'description' => 'Schutzschiff',
                'group' => UnitGroup::OFFENSE,
                'cost' => new ResourceBag(1000, 1500),
                'ticksCost' => 32,
                'carrierCapacity' => 0,
                'carrierConsumption' => 0,
                'extractorSteal' => 0,
                'extractorGuard' => 1,
                'scanRelaisFactor' => 0,
                'scanBlockerFactor' => 0,
                'cargo' => 0,
                'combats' => new UnitCombatCollection(),
            ],
        );
    }

    protected function getRubium(): Unit
    {
        return new Unit(
            ...[
                'unitId' => UnitDataInterface::RUBIUM_ID,
                'name' => 'Rubium',
                'codeName' => 'LO',
                'unitClass' => 'Leichtes Orbitalgeschütz',
                'description' => 'Leichtes Orbitalgeschütz',
                'group' => UnitGroup::DEFENSE,
                'cost' => new ResourceBag(6000, 2000),
                'ticksCost' => 18,
                'carrierCapacity' => 0,
                'carrierConsumption' => 0,
                'extractorSteal' => 0,
                'extractorGuard' => 0,
                'scanRelaisFactor' => 0,
                'scanBlockerFactor' => 0,
                'cargo' => 0,
                'combats' => new UnitCombatCollection(
                    ...[
                        new UnitCombat(UnitDataInterface::RUBIUM_ID, UnitDataInterface::LEO_ID, 0.6, 0.3, 0),
                        new UnitCombat(UnitDataInterface::RUBIUM_ID, UnitDataInterface::CLEPTOR_ID, 0.4, 1.28, 0),
                    ]
                ),
            ],
        );
    }

    protected function getPulsar(): Unit
    {
        return new Unit(
            ...[
                'unitId' => UnitDataInterface::PULSAR_ID,
                'name' => 'Pulsar',
                'codeName' => 'LR',
                'unitClass' => 'Leichtes Raumgeschütz',
                'description' => 'Leichtes Raumgeschütz',
                'group' => UnitGroup::DEFENSE,
                'cost' => new ResourceBag(20000, 10000),
                'ticksCost' => 28,
                'carrierCapacity' => 0,
                'carrierConsumption' => 0,
                'extractorSteal' => 0,
                'extractorGuard' => 0,
                'scanRelaisFactor' => 0,
                'scanBlockerFactor' => 0,
                'cargo' => 0,
                'combats' => new UnitCombatCollection(
                    ...[
                        new UnitCombat(UnitDataInterface::PULSAR_ID, UnitDataInterface::AQUILAE_ID, 0.4, 1.2, 0),
                        new UnitCombat(UnitDataInterface::PULSAR_ID, UnitDataInterface::FORNAX_ID, 0.6, 0.5334, 0),
                        // first pre strike
                        new UnitCombat(UnitDataInterface::PULSAR_ID, UnitDataInterface::FORNAX_ID, 1.0, 0.5334 * 0.5, 1),
                    ]
                ),
            ],
        );
    }

    protected function getCoon(): Unit
    {
        return new Unit(
            ...[
                'unitId' => UnitDataInterface::COON_ID,
                'name' => 'Coon',
                'codeName' => 'MR',
                'unitClass' => 'Mittleres Raumgeschütz',
                'description' => 'Mittleres Raumgeschütz',
                'group' => UnitGroup::DEFENSE,
                'cost' => new ResourceBag(60000, 100000),
                'ticksCost' => 52,
                'carrierCapacity' => 0,
                'carrierConsumption' => 0,
                'extractorSteal' => 0,
                'extractorGuard' => 0,
                'scanRelaisFactor' => 0,
                'scanBlockerFactor' => 0,
                'cargo' => 0,
                'combats' => new UnitCombatCollection(
                    ...[
                        new UnitCombat(UnitDataInterface::COON_ID, UnitDataInterface::DRACO_ID, 0.4, 0.9143, 0),
                        new UnitCombat(UnitDataInterface::COON_ID, UnitDataInterface::GORON_ID, 0.6, 0.4267, 0),
                        // first pre strike
                        new UnitCombat(UnitDataInterface::COON_ID, UnitDataInterface::DRACO_ID, 0.4, 0.9143 * 0.5, 1),
                        new UnitCombat(UnitDataInterface::COON_ID, UnitDataInterface::GORON_ID, 0.6, 0.4267 * 0.5, 1),
                    ]
                ),
            ],
        );
    }

    protected function getCenturion(): Unit
    {
        return new Unit(
            ...[
                'unitId' => UnitDataInterface::CENTURION_ID,
                'name' => 'Centurion',
                'codeName' => 'SR',
                'unitClass' => 'Schweres Raumgeschütz',
                'description' => 'Schweres Raumgeschütz',
                'group' => UnitGroup::DEFENSE,
                'cost' => new ResourceBag(200000, 300000),
                'ticksCost' => 80,
                'carrierCapacity' => 0,
                'carrierConsumption' => 0,
                'extractorSteal' => 0,
                'extractorGuard' => 0,
                'scanRelaisFactor' => 0,
                'scanBlockerFactor' => 0,
                'cargo' => 0,
                'combats' => new UnitCombatCollection(
                    ...[
                        new UnitCombat(UnitDataInterface::CENTURION_ID, UnitDataInterface::PENTALIN_ID, 0.5, 0.5, 0),
                        new UnitCombat(UnitDataInterface::CENTURION_ID, UnitDataInterface::ZENIT_ID, 0.5, 0.3774, 0),
                        // first pre strike
                        new UnitCombat(UnitDataInterface::CENTURION_ID, UnitDataInterface::PENTALIN_ID, 0.5, 0.5 * 0.6, 1),
                        new UnitCombat(UnitDataInterface::CENTURION_ID, UnitDataInterface::ZENIT_ID, 0.5, 0.3774 * 0.6, 1),
                        // second pre strike
                        new UnitCombat(UnitDataInterface::CENTURION_ID, UnitDataInterface::PENTALIN_ID, 0.5, 0.5 * 0.2, 2),
                        new UnitCombat(UnitDataInterface::CENTURION_ID, UnitDataInterface::ZENIT_ID, 0.5, 0.3774 * 0.2, 2),
                    ]
                ),
            ],
        );
    }

    protected function getHorus(): Unit
    {
        return new Unit(
            ...[
                'unitId' => UnitDataInterface::HORUS_ID,
                'name' => 'Horus',
                'codeName' => 'AJ',
                'unitClass' => 'Abfangjäger',
                'description' => 'Abfangjäger',
                'group' => UnitGroup::DEFENSE,
                'cost' => new ResourceBag(1000, 1000),
                'ticksCost' => 8,
                'carrierCapacity' => 0,
                'carrierConsumption' => 0,
                'extractorSteal' => 0,
                'extractorGuard' => 0,
                'scanRelaisFactor' => 0,
                'scanBlockerFactor' => 0,
                'cargo' => 0,
                'combats' => new UnitCombatCollection(
                    ...[
                        new UnitCombat(UnitDataInterface::HORUS_ID, UnitDataInterface::DRACO_ID, 0.4, 0.0114, 0),
                        new UnitCombat(UnitDataInterface::HORUS_ID, UnitDataInterface::CLEPTOR_ID, 0.6, 0.32, 0),
                    ]
                ),
            ],
        );
    }

    protected function getScanRelais(): Unit
    {
        return new Unit(
            ...[
                'unitId' => UnitDataInterface::SCAN_RELAIS_ID,
                'name' => 'app.unit-class-svs',
                'codeName' => 'SVS',
                'unitClass' => 'Scanverstärker',
                'description' => 'Scanverstärker',
                'group' => UnitGroup::RECON,
                'cost' => new ResourceBag(2000, 5000),
                'ticksCost' => 10,
                'carrierCapacity' => 0,
                'carrierConsumption' => 0,
                'extractorSteal' => 0,
                'extractorGuard' => 0,
                'scanRelaisFactor' => 1,
                'scanBlockerFactor' => 0,
                'cargo' => 0,
                'combats' => new UnitCombatCollection(),
            ],
        );
    }

    protected function getScanBlocker(): Unit
    {
        return new Unit(
            ...[
                'unitId' => UnitDataInterface::SCAN_BLOCKER_ID,
                'name' => 'app.unit-class-sb',
                'codeName' => 'SB',
                'unitClass' => 'Scanblocker',
                'description' => 'Scanblocker',
                'group' => UnitGroup::RECON,
                'cost' => new ResourceBag(5000, 2000),
                'ticksCost' => 10,
                'carrierCapacity' => 0,
                'carrierConsumption' => 0,
                'extractorSteal' => 0,
                'extractorGuard' => 0,
                'scanRelaisFactor' => 0,
                'scanBlockerFactor' => 1,
                'cargo' => 0,
                'combats' => new UnitCombatCollection(),
            ],
        );
    }
}
