<?php

declare(strict_types=1);

namespace App\FinancialProducts\Application\DTO;

class CreditCardResponseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $externalProductId,
        public readonly string $title,
        public readonly string $type,
        public readonly string $description,
        public readonly string $logoUrl,
        public readonly string $deepLink,
        public readonly MoneyDTO $firstYearFee,
        public readonly MoneyDTO $incentiveAmount,
        public readonly MoneyDTO $cost,
        public readonly ?BankDTO $bank
    ) {}
}

class MoneyDTO
{
    public function __construct(
        public readonly float $amount,
        public readonly string $currency
    ) {}
}

class BankDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name
    ) {}
} 