<?php

namespace App\Controller;

use App\Service\MusicCatalogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/tracks', name: 'tracks_')]
class TrackCatalogController extends AbstractController
{
    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request, MusicCatalogService $catalog): JsonResponse
    {
        $query = (string) $request->query->get('q', '');
        if ($query === '') {
            return $this->json(['error' => 'Parameter q is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $limit = (int) $request->query->get('limit', 10);
        $limit = max(1, min(50, $limit));

        $results = $catalog->searchTracks($query, $limit);

        return $this->json($results);
    }

    #[Route('/{trackId}', name: 'show', methods: ['GET'])]
    public function show(string $trackId, MusicCatalogService $catalog): JsonResponse
    {
        $track = $catalog->getTrack($trackId);

        return $this->json($track);
    }
}


