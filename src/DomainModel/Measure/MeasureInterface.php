<?php

declare(strict_types=1);

namespace App\DomainModel\Measure;

use App\DomainModel\Location\Location;
use DateTime;

interface MeasureInterface
{
    public function setDate(DateTime $date): void;

    public function getDate(): DateTime;

    public function setValue($value): void;

    public function getValue();

    public function getLocation(): Location;

    public function setLocation(Location $location);
}