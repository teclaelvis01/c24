<?php

declare(strict_types=1);

namespace App\FinancialProducts\Application\DTO;

class BankDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name
    ) {}
} 