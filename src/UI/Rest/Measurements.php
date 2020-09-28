<?php

declare(strict_types=1);

namespace App\UI\Rest;

use App\Application\Measurements\MeasurementsAppService;
use App\DomainModel\Location\LocationException;
use Exception;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;

class Measurements
{
    private MeasurementsAppService $measurementsAppService;

    public function __construct(MeasurementsAppService $measurementsAppService)
    {
        $this->measurementsAppService = $measurementsAppService;
    }

    public function getMeasurementForLocation(string $location = 'Berlin'): JsonResponse
    {
        $response = new JsonResponse();
        $measurements = $this->measurementsAppService->getDataForLocation($location);
        $response->setContent($measurements->toJson());

        return $response;
    }

    public function getMeasurementForCoords(string $lat = '48', string $lon = '12'): JsonResponse
    {
        $response = new JsonResponse();

        try {
            $measurements = $this->measurementsAppService->getDataForCoords($lat, $lon);
            $response->setContent($measurements);
        } catch (LocationException $locationException) {
            $response->setStatusCode(JsonResponse::HTTP_NOT_FOUND);
            $response->setContent($locationException->getMessage());
        } catch (JsonException|Exception $e) {
            $response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            $response->setContent($e->getMessage());
        }

        return $response;
    }
}