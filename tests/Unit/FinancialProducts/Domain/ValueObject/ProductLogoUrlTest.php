<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Domain\ValueObject;

use App\FinancialProducts\Domain\ValueObject\ProductLogoUrl;
use Codeception\Test\Unit;

class ProductLogoUrlTest extends Unit
{
    public function testCanCreateValidProductLogoUrl(): void
    {
        $logoUrl = new ProductLogoUrl('https://example.com/logo.png');
        $this->assertEquals('https://example.com/logo.png', $logoUrl->getValue());
    }

    public function testCannotCreateProductLogoUrlWithEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Logo URL cannot be empty');
        new ProductLogoUrl('');
    }

    public function testCannotCreateProductLogoUrlWithTooLongString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Logo URL cannot be longer than 500 characters');
        new ProductLogoUrl('https://example.com/' . str_repeat('a', 486));
    }

    public function testCannotCreateProductLogoUrlWithInvalidUrl(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid logo URL format');
        new ProductLogoUrl('not-a-url');
    }
} 