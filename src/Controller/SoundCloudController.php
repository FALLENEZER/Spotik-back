<?php

namespace App\Controller;

use App\Service\SoundCloudApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/soundcloud', name: 'soundcloud_')]
class SoundCloudController extends AbstractController
{
    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request, SoundCloudApiService $soundCloudApiService): JsonResponse
    {
        $query = (string) $request->query->get('q', '');
        if ($query === '') {
            return $this->json(['error' => 'Parameter q is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $limit = (int) $request->query->get('limit', 10);
        $limit = max(1, min(50, $limit));

        $results = $soundCloudApiService->searchTracks($query, $limit);

        return $this->json($results);
    }

    #[Route('/tracks/{trackId}', name: 'track', methods: ['GET'])]
    public function track(string $trackId, SoundCloudApiService $soundCloudApiService): JsonResponse
    {
        $track = $soundCloudApiService->getTrack($trackId);

        return $this->json($track);
    }
}

