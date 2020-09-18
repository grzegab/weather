<?php

declare(strict_types=1);


namespace App\DomainModel\Measure;

use App\DomainModel\Location\Location;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Save humidity measurement for date and location.
 * @package App\DomainModel\Measure
 * @ORM\Entity()
 * @ORM\Table(name="humidity")
 */
class Humidity implements MeasureInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="humidity_seq", initialValue=1)
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime", unique=false)
     */
    private DateTime $date;

    /**
     * @ORM\Column(type="integer", unique=false)
     */
    private float $value;

    /**
     * Location of measurement.
     *
     * @ORM\ManyToOne(targetEntity="App\DomainModel\Location\Location")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id", unique=false)
     */
    private Location $location;

    public function getId(): int
    {
        return $this->id;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue($value): void
    {
        $this->value = (int)$value;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): void
    {
        $this->location = $location;
    }
}