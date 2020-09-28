<?php

declare(strict_types=1);

namespace App\DomainModel\Measure;

use App\DomainModel\Location\Location;
use App\Infrastructure\Measure\MeasureRepository;

class MeasureService
{
    private MeasureRepository $repository;
    private MeasureDTO $dto;

    public function __construct(MeasureRepository $repository)
    {
        $this->repository = $repository;
        $this->dto = new MeasureDTO();
    }

    /**
     * Get data from DB for named location.
     *
     * @param Location $location
     * @return MeasureDTO|null
     */
    public function getDataForLocation(Location $location): ?MeasureDTO
    {
        /*
         * Check if there is a temperature in DB
         */
        $temperature = $this->repository->getTemperature($location);
        if ($temperature !== null) {
            $this->dto->addMeasurement(MeasureDTO::TEMP_NAME, $temperature->getValue());
        }

        /*
         * Check if there is a humidity in DB
         */
        $humidity = $this->repository->getHumidity($location);
        if ($humidity !== null) {
            $this->dto->addMeasurement(MeasureDTO::HUMID_NAME, $humidity->getValue());
        }

        /*
         * Set city name into DTO
         */
        if ($location->getCity() !== null) {
            $this->dto->setCity($location->getCity());
        }

        return $this->dto;
    }

    /**
     * Save measurements into DB.
     *
     * @param Location $location
     * @param MeasureDTO $measureDTO
     */
    public function addForecast(Location $location, MeasureDTO $measureDTO): void
    {
        $this->repository->saveForecast($location, $measureDTO);
    }
}