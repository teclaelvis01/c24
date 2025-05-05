<?php

declare(strict_types=1);

namespace App\FinancialProducts\Application\DTO;

class CreditCardEditResponseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $description,
        public readonly ?string $logoUrl,
        public readonly ?string $deepLink,
        public readonly MoneyDTO $incentiveAmount,
        public readonly MoneyDTO $cost,
        public readonly BankDTO $bank
    ) {}
}