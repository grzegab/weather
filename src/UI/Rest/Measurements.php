<?php

declare(strict_types=1);

namespace App\UI\Rest;

use App\Application\Measurements\MeasurementsAppService;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class Measurements
{
    private MeasurementsAppService $measurementsAppService;
    private Environment $twig;

    public function __construct(MeasurementsAppService $measurementsAppService, Environment $twig)
    {
        $this->measurementsAppService = $measurementsAppService;
        $this->twig = $twig;
    }

    public function getMeasurementForLocation(string $lat = '18', string $lon = '54'): Response
    {
        $response = new Response();
        $measurements = $this->measurementsAppService->getDataForLocation($lat, $lon);
        $html = $this->twig->render('Measurements/measurements.html.twig', ['measurements' => $measurements]);
        $response->setContent($html);

        return $response;
    }
}