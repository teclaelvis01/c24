<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Money
{
    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $amount;

    #[ORM\Embedded(class: Currency::class)]
    private Currency $currency;

    public function __construct(float $amount, ?Currency $currency = null)
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Amount cannot be negative');
        }
        $this->amount = $amount;
        $this->currency = $currency ?? Currency::EUR();
    }

    public static function fromString(string $amount, ?Currency $currency = null): self
    {
        if (!preg_match('/^\d+([.,]\d{1,2})?$/', $amount)) {
            throw new \InvalidArgumentException('Invalid money format');
        }

        $amount = str_replace(',', '.', $amount);
        return new self((float) $amount, $currency);
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function __toString(): string
    {
        return sprintf('%.2f %s', $this->amount, $this->currency);
    }
} 