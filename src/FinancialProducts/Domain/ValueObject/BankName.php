<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;

/**
 * <bank>* Bank name
 */
#[ORM\Embeddable]
class BankName
{
    #[ORM\Column(type: 'string', length: 500, name: 'name')]
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('Bank name cannot be empty');
        }
        if (strlen($value) > 500) {
            throw new \InvalidArgumentException('Bank name cannot be longer than 500 characters');
        }
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
} 