<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GoController extends AbstractController
{
    #[Route('/go', name: 'app_go')]
    public function index(): Response
    {
        $bob = 52 + 14;
        phpinfo();
        return $this->render('go/index.html.twig', [
            'controller_name' => 'GoController',
        ]);
    }
}
