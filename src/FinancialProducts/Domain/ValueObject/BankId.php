<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;
/**
 * 
 * <bankid> * Bank ID
 */
#[ORM\Embeddable]
class BankId
{
    #[ORM\Column(type: 'integer')]
    private int $value;

    public function __construct(int $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Bank ID must be greater than 0');
        }
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
} 