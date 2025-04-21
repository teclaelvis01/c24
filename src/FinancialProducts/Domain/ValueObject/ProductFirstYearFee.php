<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\ValueObject;


/**
 * <gebuehrenjahr1>* First year fee of the product
 */
class ProductFirstYearFee
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