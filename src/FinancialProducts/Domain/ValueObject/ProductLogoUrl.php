<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\ValueObject;


/**
 * <logo>* URL of the product logo
 */
class ProductLogoUrl
{
    
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('Logo URL cannot be empty');
        }
        if (strlen($value) > 255) {
            throw new \InvalidArgumentException('Logo URL cannot be longer than 500 characters');
        }
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Invalid logo URL format');
        }
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
} 