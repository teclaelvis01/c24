<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\Repository;

use App\FinancialProducts\Domain\Entity\Bank;

interface BankRepositoryInterface
{
    public function findByExternalId(int $externalId): ?Bank;
} 