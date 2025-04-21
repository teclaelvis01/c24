<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;

/**
 * <productid>* Product ID
 */
#[ORM\Embeddable]
class ProductId
{
    #[ORM\Column(type: 'integer')]
    private int $value;

    public function __construct(int $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Product ID must be greater than 0');
        }
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
} 