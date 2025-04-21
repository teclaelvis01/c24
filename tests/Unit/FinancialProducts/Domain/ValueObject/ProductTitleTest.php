<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Domain\ValueObject;

use App\FinancialProducts\Domain\ValueObject\ProductTitle;
use Codeception\Test\Unit;

class ProductTitleTest extends Unit
{
    public function testCanCreateValidProductTitle(): void
    {
        $title = new ProductTitle('Test Product');
        $this->assertEquals('Test Product', $title->getValue());
    }

    public function testCannotCreateProductTitleWithEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product name cannot be empty');
        new ProductTitle('');
    }

    public function testCannotCreateProductTitleWithTooLongString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product name cannot be longer than 255 characters');
        new ProductTitle(str_repeat('a', 256));
    }
} 