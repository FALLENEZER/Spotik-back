<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpotifyAuthService {
    public function __construct(private HttpClientInterface $httpClient) {}

    public function exchangeCode(string $code): string {
        $basicCreds = base64_encode($_ENV['SPOTIFY_CLIENT_ID'] . ':' . $_ENV['SPOTIFY_CLIENT_SECRET']);

        $response = $this->httpClient->request('POST', 'https://accounts.spotify.com/api/token', [
            'headers' => [
                'Authorization' => 'Basic ' . $basicCreds,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body' => http_build_query([
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $_ENV['SPOTIFY_REDIRECT_URI'],
            ]),
        ]);

        if ($response->getStatusCode() !== 200) {
            $content = $response->getContent(false);
            throw new \RuntimeException('Spotify token exchange failed: HTTP ' . $response->getStatusCode() . ' ' . $content);
        }

        $data = $response->toArray(false);
        if (!isset($data['access_token'])) {
            throw new \RuntimeException('Spotify token exchange response missing access_token');
        }

        return $data['access_token'];
    }

}
