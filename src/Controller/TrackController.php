<?php

namespace App\Controller;

use App\Entity\Track;
use App\ResponseBuilder\TrackResponseBuilder;
use App\Service\TrackService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/tracks', name: 'tracks_')]
class TrackController extends AbstractController
{

    function __construct(
        private readonly TrackService         $service,
        private readonly TrackResponseBuilder $responseBuilder,
    )
    {

    }

    #[Route(path: '/', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $track = $this->service->create($data);

        return $this->responseBuilder->createTrackResponse($track);
    }

//    #[Route(path: '/', name: 'show', methods: ['GET'])]
//    public function index(Request $request): JsonResponse
//    {
//        $name = $request->query->get('name');
//        $artist = $request->query->get('artist');
//
//        $tracks = $this->service->search($name, $artist);
//    }

//    #[Route(path: '/', name: 'search', methods: ['GET'])]
//    public function show(Request $request): JsonResponse
//    {
//
//    }

//    #[Route(path: '/{track<\d+>}', name: 'delete', methods: ['DELETE'])]
//    public function delete(Track $track): JsonResponse
//    {
//        $this->service->delete($track);
//        $this->responseBuilder->deleteTrackResponse();
//    }
}
