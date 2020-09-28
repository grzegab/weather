<?php

declare(strict_types=1);

namespace App\DomainModel\Redis;

use App\DomainModel\Measure\MeasureDTO;
use JsonException;
use Predis\Client;

class RedisService
{
    private Client $redisClient;

    public function __construct(Client $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    /**
     * Get cache value by location name.
     *
     * @param string $city
     * @return string|null
     */
    public function getByCity(string $city): ?string
    {
        return $this->redisClient->get($city);
    }

    /**
     * Set value (JSON) to cache.
     *
     * @param MeasureDTO $measureDTO
     * @throws JsonException
     */
    public function addByCity(MeasureDTO $measureDTO): void
    {
        $this->redisClient->set($measureDTO->getCity(), $measureDTO->toJson());
    }
}