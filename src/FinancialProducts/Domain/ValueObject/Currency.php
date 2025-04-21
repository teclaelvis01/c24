<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Currency
{
    private const DEFAULT_CURRENCY = 'EUR';
    private const ALLOWED_CURRENCIES = [
        'EUR' => '€',
    ];

    #[ORM\Column(type: 'string', length: 3)]
    private string $code;

    public function __construct(string $code = self::DEFAULT_CURRENCY)
    {
        $code = strtoupper($code);
        if (!array_key_exists($code, self::ALLOWED_CURRENCIES)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid currency code. Allowed currencies are: %s', implode(', ', array_keys(self::ALLOWED_CURRENCIES)))
            );
        }
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return self::ALLOWED_CURRENCIES[$this->code];
    }


    public function __toString(): string
    {
        return $this->code;
    }

    public static function EUR(): self
    {
        return new self('EUR');
    }

} 