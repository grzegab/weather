<?php

namespace App\Tests\UI\Rest;

use App\Application\Measurements\MeasurementsAppService;
use App\UI\Rest\Measurements;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class MeasurementsTest extends TestCase
{
    private const HTML = '<h1>Some HTML</h1>';

    public function testGetMeasurementForLocation(): void
    {
        $appService = $this->createStub(MeasurementsAppService::class);
        $env = $this->createStub(Environment::class);
        $env->method('render')->willReturn(self::HTML);
        $measurements = new Measurements($appService, $env);
        $response = $measurements->getMeasurementForLocation(12, 12);

        self::assertSame($response->getStatusCode(), 200);
        self::assertSame($response->getContent(), self::HTML);
    }
}
