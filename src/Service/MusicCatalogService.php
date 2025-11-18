<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Простой клиент к публичному iTunes Search API (без ключей).
 *
 * Документация: https://developer.apple.com/library/archive/documentation/AudioVideo/Conceptual/iTuneSearchAPI
 */
class MusicCatalogService
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
    ) {
    }

    /**
     * Поиск треков по строке.
     *
     * @return array<string, mixed>
     */
    public function searchTracks(string $query, int $limit = 10): array
    {
        if ($query === '') {
            return ['results' => []];
        }

        $limit = max(1, min(50, $limit));

        $response = $this->httpClient->request('GET', 'https://itunes.apple.com/search', [
            'query' => [
                'media' => 'music',
                'entity' => 'song',
                'term' => $query,
                'limit' => $limit,
            ],
        ]);

        if ($response->getStatusCode() >= 400) {
            $content = $response->getContent(false);
            throw new \RuntimeException('iTunes search failed: '.$content);
        }

        return $response->toArray(false);
    }

    /**
     * Детали одного трека по его iTunes trackId.
     *
     * @return array<string, mixed>
     */
    public function getTrack(string $trackId): array
    {
        $response = $this->httpClient->request('GET', 'https://itunes.apple.com/lookup', [
            'query' => [
                'id' => $trackId,
                'entity' => 'song',
            ],
        ]);

        if ($response->getStatusCode() >= 400) {
            $content = $response->getContent(false);
            throw new \RuntimeException('iTunes track lookup failed: '.$content);
        }

        $data = $response->toArray(false);

        if (!isset($data['results'][0])) {
            throw new \RuntimeException('Track not found in iTunes catalog');
        }

        return $data['results'][0];
    }
}


