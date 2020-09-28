<?php

declare(strict_types=1);

namespace App\Application\Measurements;

use App\DomainModel\Location\LocationException;
use App\DomainModel\Location\LocationService;
use App\DomainModel\Measure\MeasureService;
use App\DomainModel\Redis\RedisService;
use App\DomainModel\Weather\WeatherService;
use App\Infrastructure\Logger\LoggerTrait;
use JsonException;

class MeasurementsAppService
{
    use LoggerTrait;

    private MeasureService $measureService;
    private WeatherService $weatherService;
    private LocationService $locationService;
    private RedisService $redisService;

    public function __construct(
        MeasureService $measureService,
        WeatherService $weatherService,
        LocationService $locationService,
        RedisService $redisService
    ) {
        $this->measureService = $measureService;
        $this->weatherService = $weatherService;
        $this->locationService = $locationService;
        $this->redisService = $redisService;
    }

    /**
     * Return JSON string with forecast by city name.
     *
     * @param string $locationName
     * @return string
     * @throws JsonException
     * @throws LocationException
     */
    public function getDataForLocation(string $locationName): string
    {
        $location = $this->locationService->getLocationByName($locationName);

        // From trait
        $this->logEvent(sprintf('searching for location: %s', $locationName));

        if ($location === null) {
            throw LocationException::noLocationFound();
        }

        return $this->getDataForCoords((string)$location->getLat(), (string)$location->getLon());
    }

    /**
     * Return JSON string with forecast by coordination points.
     *
     * @param string $lat
     * @param string $lon
     * @return string
     * @throws JsonException
     */
    public function getDataForCoords(string $lat, string $lon): string
    {
        // From trait
        $this->logEvent(sprintf('searching for location point: %s, %s', $lat, $lon));

        /*
         * Try to get location and get value from cache
         */
        $location = $this->locationService->getLocationByCoords($lat, $lon);
        if ($location !== null && $location->getCity() !== null) {
            $measureJson = $this->redisService->getByCity($location->getCity());
            if ($measureJson !== null) {
                return $measureJson;
            }
        }

        /*
         * Check if there is result in DB
         */
        if ($location !== null && $location->getLat() !== null && $location->getLon() !== null) {
            $measureDTO = $this->measureService->getDataForLocation($location);

            if ($measureDTO !== null && !$measureDTO->isEmpty()) {
                return $measureDTO->toJson();
            }
        }

        /*
         * Need to ask API for new data and
         */
        $latFloat = $this->locationService->floorPoint($lat);
        $lonFloat = $this->locationService->floorPoint($lon);
        $measureDTO = $this->weatherService->fetchForecast($latFloat, $lonFloat);
        if ($measureDTO->getCity() !== null) {
            $this->redisService->addByCity($measureDTO);
        }

        /*
         * We can add location here because one of API serve location name.
         */
        if ($location === null) {
            $location = $this->locationService->addLocation($measureDTO->getCity(), $latFloat, $lonFloat);
        }
        $this->measureService->addForecast($location, $measureDTO);

        return $measureDTO->toJson();
    }
}