<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\ValueObject;


/**
 * <anmerkungen>* Extra information about the product
 */
class ProductExtraInfo
{
    private const MAX_LENGTH = 500;

    private ?string $value;

    public function __construct(?string $value = null)
    {
        if ($value !== null && strlen($value) > self::MAX_LENGTH) {
            throw new \InvalidArgumentException(
                sprintf('Extra info cannot be longer than %d characters', self::MAX_LENGTH)
            );
        }
        $this->value = $value;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value ?? '';
    }
} 