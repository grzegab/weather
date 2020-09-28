<?php

declare(strict_types=1);

namespace App\DomainModel\Location;

use Doctrine\ORM\Mapping as ORM;

/**
 * Describe place on earth (mainly by City and Country).
 * @package App\DomainModel\Location
 * @ORM\Entity()
 * @ORM\Table(name="location")
 */
class Location
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="location_seq", initialValue=1)
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=true)
     */
    private string $city;

    /**
     * @ORM\Column(type="float", unique=false)
     */
    private float $lon;

    /**
     * @ORM\Column(type="float", unique=false)
     */
    private float $lat;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getLon(): float
    {
        return $this->lon;
    }

    public function setLon(float $lon): void
    {
        $this->lon = $lon;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function setLat(float $lat): void
    {
        $this->lat = $lat;
    }
}