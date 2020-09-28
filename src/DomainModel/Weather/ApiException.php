<?php

declare(strict_types=1);

namespace App\DomainModel\Weather;

use Exception;

class ApiException extends Exception
{
    public static function fetchDataProblem(): ApiException
    {
        return new self('Problem with connection.');
    }
}