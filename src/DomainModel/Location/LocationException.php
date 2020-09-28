<?php

declare(strict_types=1);

namespace App\DomainModel\Location;

use Exception;

class LocationException extends Exception
{
    public static function noLocationFound(): LocationException
    {
        return new self('There is no location with this name in our database.');
    }

    public static function outOfBounds(): LocationException
    {
        return new self('Location excess search location. Lat: -59..59, Lon: -179..179');
    }
}