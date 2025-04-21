<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\ValueObject;


/**
 * <kosten>* Annual transaction costs of the credit card in Euro
 */
class Cost
{
    private Money $money;

    public function __construct(Money $money)
    {
        $this->money = $money;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function __toString(): string
    {
        return (string) $this->money;
    }
}