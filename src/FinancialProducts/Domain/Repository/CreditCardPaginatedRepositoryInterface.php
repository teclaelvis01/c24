<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\Repository;

use App\FinancialProducts\Domain\Entity\CreditCard;

interface CreditCardPaginatedRepositoryInterface
{
    /**
     * Find paginated credit cards
     *
     * @param integer $page
     * @param integer $limit
     * @param string $sortBy
     * @param string $sortOrder
     * @return array CreditCard[]
     */
    public function findPaginated(int $page = 1, int $limit = 10, string $sortBy = 'title', string $sortOrder = 'asc'): array;
    
    /**
     * Count all credit cards
     *
     * @return integer
     */
    public function countAll(): int;
} 