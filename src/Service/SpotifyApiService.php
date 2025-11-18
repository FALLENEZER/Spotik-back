<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpotifyApiService {
    public function __construct(
        private HttpClientInterface $httpClient,
    ) {}

    public function getMe(string $token): array {
        $response = $this->httpClient->request('GET', 'https://api.spotify.com/v1/me', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('Spotify API /me request failed with status ' . $response->getStatusCode());
        }

        return $response->toArray(false);
    }
}
