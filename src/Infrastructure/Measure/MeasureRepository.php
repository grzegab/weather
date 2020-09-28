<?php

declare(strict_types=1);

namespace App\Infrastructure\Measure;

use App\DomainModel\Location\Location;
use App\DomainModel\Measure\Humidity;
use App\DomainModel\Measure\MeasureDTO;
use App\DomainModel\Measure\Temperature;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class MeasureRepository
{
    private ObjectRepository $temperatureRepository;
    private ObjectRepository $humidityRepository;
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->temperatureRepository = $em->getRepository(Temperature::class);
        $this->humidityRepository = $em->getRepository(Humidity::class);
    }

    public function getTemperature(Location $location): ?Temperature
    {
        //@TODO: filter by current date

        /** @var Temperature|null $temperature */
        $temperature = $this->temperatureRepository->findOneBy(['location' => $location]);

        return $temperature;
    }

    public function getHumidity(Location $location): ?Humidity
    {
        //@TODO: filter by current date

        /** @var Humidity|null $humidity */
        $humidity = $this->humidityRepository->findOneBy(['location' => $location]);

        return $humidity;
    }

    /**
     * Save measurement from DTO into database.
     * @param Location $location
     * @param MeasureDTO $measureDTO
     */
    public function saveForecast(Location $location, MeasureDTO $measureDTO): void
    {
        $measurements = $measureDTO->getMeasurements();

        if (!empty($measurements[$measureDTO::TEMP_NAME])) {
            $temp = new Temperature();
            $temp->setDate(new DateTime());
            $temp->setLocation($location);
            $temp->setValue($measurements[$measureDTO::TEMP_NAME]);
            $this->em->persist($temp);
        }

        if (!empty($measurements[$measureDTO::HUMID_NAME])) {
            $humidity = new Humidity();
            $humidity->setDate(new DateTime());
            $humidity->setLocation($location);
            $humidity->setValue($measurements[$measureDTO::HUMID_NAME]);
            $this->em->persist($humidity);
        }

        $this->em->flush();
    }
}