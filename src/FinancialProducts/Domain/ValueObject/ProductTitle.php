<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;

/**
 * <produkt>* Product title
 */
#[ORM\Embeddable]
class ProductTitle
{
    #[ORM\Column(type: 'string', length: 255, name: 'title')]
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('Product name cannot be empty');
        }
        if (strlen($value) > 255) {
            throw new \InvalidArgumentException('Product name cannot be longer than 255 characters');
        }
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
} 