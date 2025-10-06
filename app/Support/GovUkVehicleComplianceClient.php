<?php

namespace App\Support;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;

class GovUkVehicleComplianceClient
{
    public function __construct(private readonly HttpFactory $http)
    {
    }

    /**
     * Fetch MOT expiry and road tax due dates for the given registration.
     */
    public function fetch(string $registration): array
    {
        $registration = $this->normaliseRegistration($registration);

        if ($registration === '') {
            return [
                'mot_expiry' => null,
                'road_tax_due' => null,
            ];
        }

        return [
            'mot_expiry' => $this->fetchMotExpiry($registration),
            'road_tax_due' => $this->fetchRoadTaxDue($registration),
        ];
    }

    private function fetchRoadTaxDue(string $registration): ?string
    {
        $apiKey = Arr::get(config('services.gov_uk'), 'dvla.api_key');

        if (! is_string($apiKey) || trim($apiKey) === '') {
            return null;
        }

        $response = $this->dvlaRequest()
            ->asJson()
            ->post('/vehicles', [
                'registrationNumber' => $registration,
            ]);

        if ($response->failed()) {
            return null;
        }

        return $this->normaliseDate($response->json('taxDueDate'));
    }

    private function fetchMotExpiry(string $registration): ?string
    {
        $apiKey = Arr::get(config('services.gov_uk'), 'dvsa.api_key');

        if (! is_string($apiKey) || trim($apiKey) === '') {
            return null;
        }

        $response = $this->dvsaRequest()
            ->get('/mot-tests', [
                'registration' => $registration,
            ]);

        if ($response->failed()) {
            return null;
        }

        $payload = $response->json();

        if (! is_array($payload) || $payload === []) {
            return null;
        }

        $vehicle = $payload[0];

        if (! is_array($vehicle)) {
            return null;
        }

        $date = $vehicle['motTestExpiryDate']
            ?? Arr::get($vehicle, 'motTests.0.expiryDate');

        if (! is_string($date)) {
            return null;
        }

        return $this->normaliseDate($date);
    }

    private function dvlaRequest(): PendingRequest
    {
        $config = Arr::get(config('services.gov_uk'), 'dvla', []);
        $baseUrl = is_array($config) && isset($config['base_url'])
            ? (string) $config['base_url']
            : 'https://driver-vehicle-licensing.api.gov.uk/vehicle-enquiry/v1';

        $apiKey = is_array($config) && isset($config['api_key']) ? (string) $config['api_key'] : '';

        return $this->http->baseUrl($baseUrl)
            ->withHeaders([
                'x-api-key' => $apiKey,
                'accept' => 'application/json',
            ]);
    }

    private function dvsaRequest(): PendingRequest
    {
        $config = Arr::get(config('services.gov_uk'), 'dvsa', []);
        $baseUrl = is_array($config) && isset($config['base_url'])
            ? (string) $config['base_url']
            : 'https://beta.check-mot.service.gov.uk/trade/vehicles';

        $apiKey = is_array($config) && isset($config['api_key']) ? (string) $config['api_key'] : '';

        return $this->http->baseUrl($baseUrl)
            ->withHeaders([
                'x-api-key' => $apiKey,
                'accept' => 'application/json+v6',
            ]);
    }

    private function normaliseDate(mixed $value): ?string
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        try {
            return Carbon::parse($value)->toDateString();
        } catch (InvalidFormatException) {
            return null;
        }
    }

    private function normaliseRegistration(string $registration): string
    {
        return strtoupper(str_replace(' ', '', trim($registration)));
    }
}
