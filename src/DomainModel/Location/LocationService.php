<?php

declare(strict_types=1);

namespace App\DomainModel\Location;

use App\Infrastructure\Location\LocationRepository;

class LocationService
{
    private const PRECISION = 2;
    private LocationRepository $repository;

    public function __construct(LocationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Floor coordination point using set precision
     * @param string $point
     * @return float
     * @throws LocationException
     */
    public function floorPoint(string $point): float
    {
        $floatPoint = (float)$point;
        if ($floatPoint === null) {
            throw LocationException::outOfBounds();
        }

        $precision = 10 ** self::PRECISION;

        return (floor($floatPoint * $precision) / $precision);
    }

    /**
     * Check if lat and long are valid (API limitation):
     * Latitude, -59 to 59
     * Longitude, -179 to 179
     *
     * @param string $lat
     * @param string $lon
     * @throws LocationException
     */
    private function verifyCoords(string $lat, string $lon): void
    {
        if ((int)$lat < -59 || (int)$lat > 59) {
            throw LocationException::outOfBounds();
        }
        if ((int)$lon < -179 || (int)$lon > 179) {
            throw LocationException::outOfBounds();
        }
    }

    /**
     * Check of cords are valid and search for location in DB.
     *
     * @param string $lat
     * @param string $lon
     * @return Location|null
     * @throws LocationException
     */
    public function getLocationByCoords(string $lat, string $lon): ?Location
    {
        $this->verifyCoords($lat, $lon);
        $latFloat = $this->floorPoint($lat);
        $lonFloat = $this->floorPoint($lon);

        return $this->repository->findByCords($latFloat, $lonFloat);
    }

    /**
     * Search location by name.
     *
     * @param string $location
     * @return Location|null
     */
    public function getLocationByName(string $location): ?Location
    {
        return $this->repository->findByLocation($location);
    }

    /**
     * Save location to DB.
     *
     * @param string|null $cityName
     * @param float $lat
     * @param float $lon
     * @return Location
     */
    public function addLocation(?string $cityName, float $lat, float $lon): Location
    {
        return $this->repository->saveLocation($cityName, $lat, $lon);
    }
}