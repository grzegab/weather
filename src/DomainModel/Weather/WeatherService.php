<?php

declare(strict_types=1);

namespace App\DomainModel\Weather;

use App\DomainModel\Measure\MeasureDTO;
use App\Infrastructure\Logger\LoggerTrait;
use DateTime;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherService
{
    use LoggerTrait;

    private const API_METHOD = 'GET';
    private HttpClientInterface $httpClient;
    private string $openWeatherApiKey;
    private string $climaCellApiKey;
    private string $stormGlassApiKey;
    private MeasureDTO $measure;

    public function __construct(
        HttpClientInterface $httpClient,
        string $openWeatherAPI,
        string $climaCellAPI,
        string $stormGlassAPI
    ) {
        $this->httpClient = $httpClient;
        $this->openWeatherApiKey = $openWeatherAPI;
        $this->climaCellApiKey = $climaCellAPI;
        $this->stormGlassApiKey = $stormGlassAPI;
        $this->measure = new MeasureDTO;
    }

    public function fetchForecast(float $lat, float $lon): MeasureDTO
    {
        // Fetch data and if not empty add to MeasureDTO
        $this->openWeatherData((string)$lat, (string)$lon);
        $this->climaCellData((string)$lat, (string)$lon);
        $this->stormGlassData((string)$lat, (string)$lon);

        return $this->measure;
    }

    /**
     * @param $url
     * @param array $extra
     * @return string
     * @throws ApiException
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function fetchData($url, $extra = []): string
    {
        try {
            $response = $this->httpClient->request(self::API_METHOD, $url, $extra);
        } catch (Exception $e) {
            throw ApiException::fetchDataProblem();
        }

        return $response->getContent();
    }


    private function openWeatherData(string $lat, string $lon): void
    {
        $openWeatherEndpoint = sprintf(
            'https://api.openweathermap.org/data/2.5/weather?lat=%s&lon=%s&units=metric&appid=%s',
            $lat,
            $lon,
            $this->openWeatherApiKey
        );
        try {
            $jsonResponse = $this->fetchData($openWeatherEndpoint);
            $openWeatherRespArray = json_decode($jsonResponse, true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            $this->logEvent(sprintf('Problem fetching OpenWeather: %s', $e->getMessage()));
        }

        if (!empty($openWeatherRespArray['main']['temp'])) {
            $this->measure->addMeasurement(MeasureDTO::TEMP_NAME, $openWeatherRespArray['main']['temp']);
        }
        if (!empty($openWeatherRespArray['main']['humidity'])) {
            $this->measure->addMeasurement(MeasureDTO::HUMID_NAME, $openWeatherRespArray['main']['humidity']);
        }
        if (!empty($openWeatherRespArray['name'])) {
            $this->measure->setCity($openWeatherRespArray['name']);
        }
    }

    private function climaCellData(string $lat, string $lon): void
    {
        $climaCellEndpoint = sprintf(
            'https://api.climacell.co/v3/weather/realtime?lat=%s&lon=%s&unit_system=si&fields=temp,humidity&apikey=%s',
            $lat,
            $lon,
            $this->climaCellApiKey
        );

        try {
            $jsonResponse = $this->fetchData($climaCellEndpoint);
            $climaCellResponseArray = json_decode($jsonResponse, true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            $this->logEvent(sprintf('Problem fetching ClimaCell: %s', $e->getMessage()));
        }

        if (!empty($climaCellResponseArray['temp']['value'])) {
            $this->measure->addMeasurement(MeasureDTO::TEMP_NAME, $climaCellResponseArray['temp']['value']);
        }
        if (!empty($climaCellResponseArray['humidity']['value'])) {
            $this->measure->addMeasurement(MeasureDTO::HUMID_NAME, $climaCellResponseArray['humidity']['value']);
        }
    }

    private function stormGlassData(string $lat, string $lon): void
    {
        $currentTime = new DateTime();
        $stormGlassEndpoint = sprintf(
            'https://api.stormglass.io/v2/weather/point?lat=%s&lng=%s&params=airTemperature,humidity&start=%s&end=%s',
            $lat,
            $lon,
            $currentTime->getTimestamp(),
            $currentTime->getTimestamp()
        );
        $extraParameters = ['headers' => ['Authorization' => $this->stormGlassApiKey]];

        try {
            $jsonResponse = $this->fetchData($stormGlassEndpoint, $extraParameters);
            $stormGlassResponseArray = json_decode($jsonResponse, true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            $this->logEvent(sprintf('Problem fetching StormGlass: %s', $e->getMessage()));
        }

        if (!empty($stormGlassResponseArray['hours'][0]['airTemperature']['noaa'])) {
            $this->measure->addMeasurement(
                MeasureDTO::TEMP_NAME,
                $stormGlassResponseArray['hours'][0]['airTemperature']['noaa']
            );
        }
        if (!empty($stormGlassResponseArray['hours'][0]['humidity']['noaa'])) {
            $this->measure->addMeasurement(
                MeasureDTO::HUMID_NAME,
                $stormGlassResponseArray['hours'][0]['humidity']['noaa']
            );
        }
    }
}