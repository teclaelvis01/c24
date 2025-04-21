<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\ValueObject;

/**
 * <link>* Deep link to the product
 */
class ProductDeepLink
{
    
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('Deep link cannot be empty');
        }
        if (strlen($value) > 255) {
            throw new \InvalidArgumentException('Deep link cannot be longer than 500 characters');
        }
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Invalid deep link URL format');
        }
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

} 