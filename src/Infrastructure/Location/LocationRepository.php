<?php

declare(strict_types=1);

namespace App\Infrastructure\Location;

use App\DomainModel\Location\Location;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class LocationRepository
{
    private ObjectRepository $locationRepository;
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->locationRepository = $em->getRepository(Location::class);
        $this->em = $em;
    }

    public function findByCords(float $lat, float $lon): ?Location
    {
        /** @var Location|null $location */
        $location = $this->locationRepository->findOneBy(['lat' => $lat, 'lon' => $lon]);

        return $location;
    }

    public function findByLocation(string $location): ?Location
    {
        /** @var Location|null $locationByCity */
        $locationByCity = $this->locationRepository->findOneBy(['city' => $location]);

        return $locationByCity;
    }

    /**
     * Saves new location.
     *
     * @param string|null $cityName
     * @param float $lat
     * @param float $lon
     * @return Location
     */
    public function saveLocation(?string $cityName, float $lat, float $lon): Location
    {
        $location = new Location();
        if ($cityName !== null) {
            $location->setCity($cityName);
        }
        $location->setLat($lat);
        $location->setLon($lon);

        $this->em->persist($location);
        $this->em->flush();

        return $location;
    }
}