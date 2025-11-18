<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExternalApiService {
    private HttpClientInterface $client;
    private string $baseUrl;

    public function __construct(HttpClientInterface $client, string $baseUrl) {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
    }

    public function getData(string $endpoint): array {
        $response = $this->client->request('GET', $this->baseUrl . $endpoint);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('API request failed');
        }

        return $response->toArray();
    }
}
