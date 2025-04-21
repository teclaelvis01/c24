<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Domain\ValueObject;

use App\FinancialProducts\Domain\ValueObject\ProductDeepLink;
use Codeception\Test\Unit;

class ProductDeepLinkTest extends Unit
{
    public function testCanCreateValidProductDeepLink(): void
    {
        $deepLink = new ProductDeepLink('https://example.com/product/123');
        $this->assertEquals('https://example.com/product/123', $deepLink->getValue());
    }

    public function testCannotCreateProductDeepLinkWithEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Deep link cannot be empty');
        new ProductDeepLink('');
    }

    public function testCannotCreateProductDeepLinkWithTooLongString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Deep link cannot be longer than 500 characters');
        new ProductDeepLink('https://example.com/' . str_repeat('a', 486));
    }

    public function testCannotCreateProductDeepLinkWithInvalidUrl(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid deep link URL format');
        new ProductDeepLink('not-a-url');
    }
} 