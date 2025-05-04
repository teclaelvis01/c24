<?php

declare(strict_types=1);

namespace App\FinancialProducts\Application\DTO;

class MoneyDTO
{
    public function __construct(
        public readonly float $amount,
        public readonly string $currency
    ) {}
} 