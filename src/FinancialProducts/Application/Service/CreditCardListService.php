<?php

declare(strict_types=1);

namespace App\FinancialProducts\Application\Service;

use App\FinancialProducts\Domain\Repository\CreditCardPaginatedRepositoryInterface;
use App\FinancialProducts\Application\Mapper\CreditCardEditResponseMapper;
use App\FinancialProducts\Application\DTO\CreditCardResponseDTO;
use App\FinancialProducts\Application\DTO\MoneyDTO;
use App\FinancialProducts\Domain\Entity\CreditCardManualEdit;

class CreditCardListService
{
    public function __construct(
        private readonly CreditCardPaginatedRepositoryInterface $creditCardRepository,
        private readonly CreditCardEditResponseMapper $mapper
    ) {}

    /**
     * @return array{data: array<CreditCardResponseDTO>, pagination: array{total: int, page: int, limit: int, pages: int}}
     */
    public function getPaginatedCards(
        int $page = 1, 
        int $limit = 10,
        string $sortBy = 'title',
        string $sortOrder = 'asc'
    ): array {
        $cards = $this->creditCardRepository->findPaginated($page, $limit, $sortBy, $sortOrder);
        $total = $this->creditCardRepository->countAll();
        
        $mappedCards = array_map(
            function($card) {
                $dto = $this->mapper->toDTO($card);
                return $dto;
            },
            $cards
        );
        
        return [
            'data' => $mappedCards,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'pages' => (int) ceil($total / $limit)
            ]
        ];
    }

    private function createUpdatedDTO(CreditCardResponseDTO $originalDTO, CreditCardManualEdit $manualEdit): CreditCardResponseDTO
    {
        $title = $manualEdit->getTitle() ?? $originalDTO->title;
        $description = $manualEdit->getDescription() ?? $originalDTO->description;
        
        $incentiveAmount = $manualEdit->getIncentiveAmount() 
            ? new MoneyDTO(
                $manualEdit->getIncentiveAmount()->getAmount(),
                $manualEdit->getIncentiveAmount()->getCurrency()->__toString()
            )
            : $originalDTO->incentiveAmount;
            
        $cost = $manualEdit->getCost()
            ? new MoneyDTO(
                $manualEdit->getCost()->getAmount(),
                $manualEdit->getCost()->getCurrency()->__toString()
            )
            : $originalDTO->cost;

        return new CreditCardResponseDTO(
            $originalDTO->id,
            $originalDTO->externalProductId,
            $title,
            $originalDTO->type,
            $description,
            $originalDTO->logoUrl,
            $originalDTO->deepLink,
            $originalDTO->firstYearFee,
            $incentiveAmount,
            $cost,
            $originalDTO->bank
        );
    }
} 