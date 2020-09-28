<?php

namespace App\Tests\UI\Rest;

use App\Application\Measurements\MeasurementsAppService;
use App\DomainModel\Measure\MeasureDTO;
use App\UI\Rest\Measurements;
use PHPUnit\Framework\TestCase;

class MeasurementsTest extends TestCase
{
    private const JSON = '{Fake: json}';

    public function testGetMeasurementForLocation(): void
    {
        $appService = $this->createStub(MeasurementsAppService::class);
        $measureDTO = $this->createStub(MeasureDTO::class);
        $measureDTO->method('toJson')->willReturn(self::JSON);
        $appService->method('getDataForLocation')->willReturn($measureDTO);
        $measurements = new Measurements($appService);
        $response = $measurements->getMeasurementForLocation('Berlin');
        self::assertSame($response->getStatusCode(), 200);
        self::assertSame($response->getContent(), self::JSON);
    }

    public function testGetMeasurementForCoords(): void
    {
        $appService = $this->createStub(MeasurementsAppService::class);
        $measureDTO = $this->createStub(MeasureDTO::class);
        $measureDTO->method('toJson')->willReturn(self::JSON);
        $appService->method('getDataForCoords')->willReturn($measureDTO);
        $measurements = new Measurements($appService);
        $response = $measurements->getMeasurementForCoords(12, 12);

        self::assertSame($response->getStatusCode(), 200);
        self::assertSame($response->getContent(), self::JSON);
    }
}
