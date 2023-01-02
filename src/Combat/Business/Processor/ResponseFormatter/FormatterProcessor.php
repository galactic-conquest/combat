<?php

declare(strict_types=1);

namespace GC\Combat\Business\Processor\ResponseFormatter;

use GC\Combat\Business\Extractor\ExtractorBag;
use GC\Combat\Business\Resource\ResourceBag;
use GC\Combat\Transfer\Fleet;
use GC\Combat\Transfer\FleetUnitBag;
use GC\Combat\Transfer\FormatRequest;
use GC\Combat\Transfer\Player;
use GC\Combat\Transfer\Unit;
use GC\Combat\Transfer\UnitCollection;
use stdClass;

use function array_filter;
use function json_encode;

final class FormatterProcessor implements FormatterProcessorInterface
{

    public function formatJson(FormatRequest $request): string
    {
        return json_encode(
            $this->formatArray($request),
            JSON_THROW_ON_ERROR
                | JSON_HEX_TAG
                | JSON_HEX_APOS
                | JSON_HEX_AMP
                | JSON_HEX_QUOT
                | JSON_INVALID_UTF8_SUBSTITUTE
                | JSON_UNESCAPED_SLASHES
                | JSON_UNESCAPED_UNICODE
                | JSON_PRESERVE_ZERO_FRACTION
        );
    }

    /**
     * @return array<string,mixed>
     */
    public function formatArray(FormatRequest $request): array
    {
        $response = [
            'target' => $this->createTargetResponse($request),
            'attackers' => $this->createAttackersResponse($request),
        ];

        if ($request->combatResponse->battleAfter->defenders->hasPlayers()) {
            $response['defenders'] = $this->createDefendersResponse($request);
        }

        if ($request->includeConfig) {
            $response['config'] = $this->createConfigResponse($request);
        }

        return $response;
    }

    /**
     * @return array<string,mixed>
     */
    private function createTargetResponse(FormatRequest $request): array
    {
        $combatResponse = $request->combatResponse;

        $before = [];
        foreach ($combatResponse->battleBefore->target->fleets->getAll() as $fleet) {
            $before[] = $this->createFleetBeforeResponse($fleet, $request);
        }

        $after = [];
        foreach ($combatResponse->battleAfter->target->fleets->getAll() as $fleet) {
            $after[] = $this->createTargetFleetResponse($fleet, $request);
        }

        $target = [];

        if ($request->includeIds) {
            $target['playerId'] = $combatResponse->battleAfter->target->playerId;
        }

        if ($combatResponse->battleAfter->target->galaxyNumber !== null) {
            $target['galaxyNumber'] = $combatResponse->battleAfter->target->galaxyNumber;
            $target['galaxyPosition'] = $combatResponse->battleAfter->target->galaxyPosition;
        }

        $target += [
            'name' => $combatResponse->battleAfter->target->name,
            'fleets' => [
                'before' => $before,
                'after' => $after,
            ],
            'extractors' => [
                'before' => $this->createExtractorBagResponse($combatResponse->battleBefore->target->extractors),
                'after' => $this->createExtractorBagResponse($combatResponse->battleAfter->target->extractors),
                'stolen' => $this->createExtractorBagResponse($combatResponse->battleAfter->target->stolenExtractors),
            ],
            'resources' => [
                'salvaged' => $this->createResourceBagResponse($combatResponse->battleAfter->target->salvaged),
            ],
        ];

        return $target;
    }

    /**
     * @return array<string,int>
     */
    private function createExtractorBagResponse(ExtractorBag $extractorBag): array
    {
        return [
            'metal' => $extractorBag->getMetal(),
            'crystal' => $extractorBag->getCrystal(),
        ];
    }

    /**
     * @return array<string,int>
     */
    private function createResourceBagResponse(ResourceBag $resourceBag): array
    {
        return [
            'metal' => $resourceBag->getMetal(),
            'crystal' => $resourceBag->getCrystal(),
        ];
    }

    /**
     * @return array<string,mixed>
     */
    private function createTargetFleetResponse(Fleet $fleet, FormatRequest $request): array
    {
        $fleetArray = [];

        if ($request->includeIds) {
            $fleetArray['fleetId'] = $fleet->fleetId;
        }

        $fleetArray += [
            'name' => $fleet->name,
            'units' => $this->parseFleetUnitBag($fleet->units, $request),
            'losses' => $this->parseFleetUnitBag($fleet->losses, $request),
            'destroyed' => $this->parseFleetUnitBag($fleet->destroyed, $request),
            'resources' => [
                'cost' => $this->createResourceBagResponse($fleet->cost),
            ],
        ];

        return $fleetArray;
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    private function createAttackersResponse(FormatRequest $request): array
    {
        $attackers = [];

        foreach ($request->combatResponse->battleAfter->attackers->getAll() as $afterPlayer) {
            $attackers[] = $this->createAttackerResponse($afterPlayer, $request);
        }

        return $attackers;
    }

    /**
     * @return array<string,mixed>
     */
    private function createAttackerResponse(Player $afterPlayer, FormatRequest $request): array
    {
        $fleetCollectionBefore = $request->combatResponse->battleBefore
            ->attackers
            ->getPlayerById($afterPlayer->playerId)
            ->fleets;

        $before = [];
        foreach ($fleetCollectionBefore->getAll() as $fleet) {
            $before[] = $this->createFleetBeforeResponse($fleet, $request);
        }

        $after = [];
        foreach ($afterPlayer->fleets->getAll() as $fleet) {
            $after[] = $this->createAttackerFleetAfterResponse($fleet, $request);
        }

        $attacker = [];

        if ($request->includeIds) {
            $attacker['playerId'] = $afterPlayer->playerId;
        }

        if ($afterPlayer->galaxyNumber !== null) {
            $attacker['galaxyNumber'] = $afterPlayer->galaxyNumber;
            $attacker['galaxyPosition'] = $afterPlayer->galaxyPosition;
        }

        $attacker += [
            'name' => $afterPlayer->name,
            'fleets' => [
                'before' => $before,
                'after' => $after ,
            ]
        ];

        return $attacker;
    }

    /**
     * @return array<string,mixed>
     */
    private function createFleetBeforeResponse(Fleet $fleet, FormatRequest $request): array
    {
        $fleetArray = [];

        if ($request->includeIds) {
            $fleetArray['fleetId'] = $fleet->fleetId;
        }

        $fleetArray += [
            'name' => $fleet->name,
            'units' => $this->parseFleetUnitBag($fleet->units, $request),
        ];

        return $fleetArray;
    }

    /**
     * @return array<string,mixed>
     */
    private function createAttackerFleetAfterResponse(Fleet $fleet, FormatRequest $request): array
    {
        $fleetArray = [];

        if ($request->includeIds) {
            $fleetArray['fleetId'] = $fleet->fleetId;
        }

        $fleetArray += [
            'name' => $fleet->name,
            'units' => $this->parseFleetUnitBag($fleet->units, $request),
            'losses' => $this->parseFleetUnitBag($fleet->losses, $request),
            'destroyed' => $this->parseFleetUnitBag($fleet->destroyed, $request),
            'carrierCapacityLosses' => $this->parseFleetUnitBag($fleet->carrierCapacityLosses, $request),
            'extractorLosses' => $this->parseFleetUnitBag($fleet->extractorLosses, $request),
            'resources' => [
                'cost' => $this->createResourceBagResponse($fleet->cost),
            ],
            'extractors' => [
                'stolen' => $this->createExtractorBagResponse($fleet->stolenExtractors),
            ]
        ];

        return $fleetArray;
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    private function createDefendersResponse(FormatRequest $request): array
    {
        $defenders = [];

        foreach ($request->combatResponse->battleAfter->defenders->getAll() as $afterPlayer) {
            $defenders[] = $this->createDefenderResponse($afterPlayer, $request);
        }

        return $defenders;
    }

    /**
     * @return array<string,mixed>
     */
    private function createDefenderResponse(Player $afterPlayer, FormatRequest $request): array
    {
        $fleetCollectionBefore = $request->combatResponse->battleBefore
            ->defenders
            ->getPlayerById($afterPlayer->playerId)
            ->fleets;

        $before = [];
        foreach ($fleetCollectionBefore->getAll() as $fleet) {
            $before[] = $this->createFleetBeforeResponse($fleet, $request);
        }

        $after = [];
        foreach ($afterPlayer->fleets->getAll() as $fleet) {
            $after[] = $this->createDefenderFleetAfterResponse($fleet, $request);
        }

        $defender = [];

        if ($request->includeIds) {
            $defender['playerId'] = $afterPlayer->playerId;
        }

        if ($afterPlayer->galaxyNumber !== null) {
            $defender['galaxyNumber'] = $afterPlayer->galaxyNumber;
            $defender['galaxyPosition'] = $afterPlayer->galaxyPosition;
        }

        $defender += [
            'name' => $afterPlayer->name,
            'fleets' => [
                'before' => $before,
                'after' => $after ,
            ]
        ];

        return $defender;
    }

    /**
     * @return array<string,mixed>
     */
    private function createDefenderFleetAfterResponse(Fleet $fleet, FormatRequest $request): array
    {
        $fleetArray = [];

        if ($request->includeIds) {
            $fleetArray['fleetId'] = $fleet->fleetId;
        }

        $fleetArray += [
            'name' => $fleet->name,
            'units' => $this->parseFleetUnitBag($fleet->units, $request),
            'losses' => $this->parseFleetUnitBag($fleet->losses, $request),
            'destroyed' => $this->parseFleetUnitBag($fleet->destroyed, $request),
            'carrierCapacityLosses' => $this->parseFleetUnitBag($fleet->carrierCapacityLosses, $request),
            'resources' => [
                'cost' => $this->createResourceBagResponse($fleet->cost),
                'salvaged' => $this->createResourceBagResponse($fleet->salvaged),
            ],
        ];

        return $fleetArray;
    }

    /**
     * @return array<string,mixed>
     */
    private function createConfigResponse(FormatRequest $request): array
    {
        $config = [
            'mode' => $request->combatResponse->battleConfig->mode,
            'salvageTargetRatio' => $request->combatResponse->battleConfig->salvageTargetRatio,
            'salvageDefenderRatio' => $request->combatResponse->battleConfig->salvageDefenderRatio,
            'extractorStealRatio' => $request->combatResponse->battleConfig->extractorStealRatio,
        ];


        if ($request->includeConfigUnits) {
            $config['units'] = $this->createUnitCollectionResponse(
                $request->combatResponse->battleConfig->unitCollection
            );
        }

        return $config;
    }


    /**
     * @return array<array<string,mixed>>
     */
    private function createUnitCollectionResponse(UnitCollection $unitCollection): array
    {
        $units = [];

        foreach ($unitCollection->getAll() as $unit) {
            $units[] = [
                'unitId' => $unit->unitId,
                'name' => $unit->name,
                'codeName' => $unit->codeName,
                'unitClass' => $unit->unitClass,
                'group' => $unit->group->value,
                'resources' => [
                    'cargo' => $unit->cargo,
                    'cost' => $this->createResourceBagResponse($unit->cost)
                ],
                'extractors' => [
                    'steal' => $unit->extractorSteal,
                    'guard' => $unit->extractorGuard,
                ],
                'carriers' => [
                    'capacity' => $unit->carrierCapacity,
                    'consumption' => $unit->carrierConsumption,
                ],
                'combatSettings' => $this->createUnitCombatSettingsResponse($unit),
            ];
        }

        return $units;
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    private function createUnitCombatSettingsResponse(Unit $unit): array
    {
        $unitCombats = [];

        foreach ($unit->combats->getAll() as $unitCombat) {
            $unitCombats[] = [
                'targetUnitId' => $unitCombat->targetUnitId,
                'ratio' => $unitCombat->ratio,
                'attackPower' => $unitCombat->attackPower,
                'mode' => $unitCombat->mode,
            ];
        }

        return $unitCombats;
    }

    private function parseFleetUnitBag(FleetUnitBag $unitBag, FormatRequest $request): stdClass
    {
        $units = [];

        foreach (array_filter($unitBag->getAll()) as $unitId => $quantity) {
            $key = $unitId;

            if ($request->useUnitCodeName) {
                $key = $request->combatResponse->battleConfig->unitCollection->getById($unitId)->codeName;
            }

            $units[$key] = $quantity;
        }

        return (object) $units;
    }
}
