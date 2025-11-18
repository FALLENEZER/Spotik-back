<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SoundCloudApiService
{
    private ?string $accessToken = null;
    private ?\DateTimeImmutable $tokenExpiresAt = null;

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        #[\SensitiveParameter] private readonly string $clientId,
        #[\SensitiveParameter] private readonly string $clientSecret,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function searchTracks(string $query, int $limit = 10): array
    {
        if ($query === '') {
            return ['collection' => []];
        }

        $response = $this->httpClient->request('GET', 'https://api.soundcloud.com/tracks', [
            'headers' => $this->getAuthHeaders(),
            'query' => [
                'q' => $query,
                'limit' => $limit,
                'linked_partitioning' => 'false',
                'access' => 'playable',
            ],
        ]);

        if ($response->getStatusCode() >= 400) {
            $content = $response->getContent(false);
            throw new \RuntimeException('SoundCloud search failed: ' . $content);
        }

        return $response->toArray(false);
    }

    /**
     * @return array<string, mixed>
     */
    public function getTrack(string $trackId): array
    {
        $response = $this->httpClient->request('GET', sprintf('https://api.soundcloud.com/tracks/%s', $trackId), [
            'headers' => $this->getAuthHeaders(),
        ]);

        if ($response->getStatusCode() >= 400) {
            $content = $response->getContent(false);
            throw new \RuntimeException(sprintf('SoundCloud track %s fetch failed: %s', $trackId, $content));
        }

        return $response->toArray(false);
    }

    /**
     * @return array<string, string>
     */
    private function getAuthHeaders(): array
    {
        $this->ensureAccessToken();

        return [
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json; charset=utf-8',
        ];
    }

    private function ensureAccessToken(): void
    {
        $now = new \DateTimeImmutable();
        if ($this->accessToken !== null && $this->tokenExpiresAt instanceof \DateTimeImmutable && $now < $this->tokenExpiresAt) {
            return;
        }

        try {
            $response = $this->httpClient->request('POST', 'https://secure.soundcloud.com/oauth/token', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => 'application/json; charset=utf-8',
                ],
                'body' => http_build_query([
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ]),
            ]);
        } catch (TransportExceptionInterface $exception) {
            throw new \RuntimeException('SoundCloud auth failed: ' . $exception->getMessage(), previous: $exception);
        }

        if ($response->getStatusCode() >= 400) {
            $content = $response->getContent(false);
            throw new \RuntimeException('SoundCloud token request failed: ' . $content);
        }

        $data = $response->toArray(false);
        if (!isset($data['access_token'])) {
            throw new \RuntimeException('SoundCloud token response missing access_token');
        }

        $expiresIn = isset($data['expires_in']) ? (int) $data['expires_in'] : 3500;
        $this->accessToken = $data['access_token'];
        $this->tokenExpiresAt = $now->modify(sprintf('+%d seconds', max(60, $expiresIn - 60)));
    }
}

