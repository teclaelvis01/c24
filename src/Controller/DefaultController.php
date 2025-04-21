<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'version' => '1.0.0',
            'name' => 'Financial Products API',
            'environment' => $this->getParameter('kernel.environment'),
        ]);
    }
} 