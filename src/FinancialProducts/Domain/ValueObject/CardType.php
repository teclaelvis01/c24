<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\ValueObject;


enum CardTypeEnum: string
{
    case CREDIT = 'credit';
    case DEBIT = 'debit';
}

/**
 * <cardtype_text>* 
 */
class CardType
{
    public const CREDIT = CardTypeEnum::CREDIT;
    public const DEBIT = CardTypeEnum::DEBIT;

    private string $value;

    public function __construct(string $type)
    {
        try {
            $type = strtolower($type);
            $cardTypeEnum = CardTypeEnum::from($type);
            if (!in_array($cardTypeEnum, [CardTypeEnum::CREDIT, CardTypeEnum::DEBIT], true)) {
                throw new \InvalidArgumentException(
                    sprintf('Invalid card type. Must be one of: %s', implode(', ', [CardTypeEnum::CREDIT->value, CardTypeEnum::DEBIT->value]))
                );
            }
            $this->value = $cardTypeEnum->value;
        } catch (\ValueError $e) {
            throw new \InvalidArgumentException(
                sprintf('Invalid card type. Must be one of: %s', implode(', ', [CardTypeEnum::CREDIT->value, CardTypeEnum::DEBIT->value]))
            );
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isCredit(): bool
    {
        return $this->value === self::CREDIT->value;
    }

    public function isDebit(): bool
    {
        return $this->value === self::DEBIT->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
} 