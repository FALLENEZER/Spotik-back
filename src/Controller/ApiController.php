<?php

namespace App\Controller;

use App\Service\ExternalApiService;
use App\Service\SpotifyApiService;
use App\Service\SpotifyAuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController {
    #[Route('/fetch-data', name: 'fetch_data')]
    public function fetchData(ExternalApiService $externalApiService): JsonResponse {
        $data = $externalApiService->getData('/me');
        return $this->json($data);
    }

    #[Route('/callback', name: 'spotify_callback')]
    public function spotifyCallback(Request $request, SpotifyAuthService $authService): Response {
        $code = $request->query->get('code');

        if (!$code) {
            return new JsonResponse(['error' => 'Missing code parameter'], 400);
        }

        try {
            $accessToken = $authService->exchangeCode($code);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => 'Failed to exchange code', 'details' => $e->getMessage()], 400);
        }

        $request->getSession()->set('spotify_access_token', $accessToken);

        return $this->redirectToRoute('spotify_me');
    }

    #[Route('/spotify/me', name: 'spotify_me')]
    public function me(SpotifyApiService $spotify, Request $request): JsonResponse {
        $token = $request->getSession()->get('spotify_access_token');

        if (!$token) {
            return new JsonResponse(['error' => 'No access token'], 401);
        }

        try {
            $me = $spotify->getMe($token);
            return new JsonResponse($me);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => 'Failed to fetch profile', 'details' => $e->getMessage()], 400);
        }
    }

    #[Route('/login', name: 'spotify_login')]
    public function login(): Response {
        $state = bin2hex(random_bytes(16));
        $scope = 'user-read-private user-read-email';
        $clientId = $_ENV['SPOTIFY_CLIENT_ID'];
        $redirectUri = $_ENV['SPOTIFY_REDIRECT_URI'];

        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => $clientId,
            'scope' => $scope,
            'redirect_uri' => $redirectUri,
            'state' => $state,
        ]);

        return $this->redirect('https://accounts.spotify.com/authorize?' . $query);
    }
}
