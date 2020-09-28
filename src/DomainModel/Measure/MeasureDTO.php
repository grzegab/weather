<?php

declare(strict_types=1);

namespace App\DomainModel\Measure;

use JsonException;

class MeasureDTO
{
    public const TEMP_NAME = 'temperature';
    public const HUMID_NAME = 'humidity';
    private ?string $city = null;
    private array $measurements = [];

    /**
     * Check if there are measurements.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->measurements);
    }

    /**
     * Return JSON value of measurements.
     *
     * @return string
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->measurements, JSON_THROW_ON_ERROR);
    }

    public function addMeasurement($key, $value): void
    {
        if (empty($this->measurements[$key])) {
            $this->measurements[$key] = $value;
        } else {
            //@TODO: This fake algorithm can be enhanced
            $this->measurements[$key] = ($this->measurements[$key] + $value) / 2;
        }
    }

    public function getMeasurements(): array
    {
        return $this->measurements;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }
}