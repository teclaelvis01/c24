<?php

declare(strict_types=1);

namespace App\Controller;

use App\FinancialProducts\Application\Service\CreditCardListService;
use App\FinancialProducts\Application\Service\CreditCardUpdateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    public function __construct(
        private readonly CreditCardListService $creditCardListService,
        private readonly CreditCardUpdateService $creditCardUpdateService
    ) {}

    #[Route('/api/cards', name: 'api_cards_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $sortBy = $request->query->get('sort_by', 'title');
        $sortOrder = $request->query->get('sort_order', 'asc');
        
        if ($page <= 0) {
            return $this->json([
                'error' => 'Page number must be greater than 0'
            ], 400);
        }
        
        $response = $this->creditCardListService->getPaginatedCards($page, $limit, $sortBy, $sortOrder);
        
        return $this->json($response);
    }

    #[Route('/api/cards/{id}', name: 'api_cards_update', methods: ['PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json([
                'error' => 'Invalid JSON format'
            ], 400);
        }

        try {
            $this->creditCardUpdateService->updateCard(
                $id,
                $data['title'] ?? null,
                $data['description'] ?? null,
                $data['incentiveAmount'] ?? null,
                $data['cost'] ?? null
            );

            return $this->json([
                'message' => 'Card updated successfully'
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
} 