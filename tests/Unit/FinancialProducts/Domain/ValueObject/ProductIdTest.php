<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Domain\ValueObject;

use App\FinancialProducts\Domain\ValueObject\ProductId;
use Codeception\Test\Unit;

class ProductIdTest extends Unit
{
    public function testCanCreateValidProductId(): void
    {
        $productId = new ProductId(1);
        $this->assertEquals(1, $productId->getValue());
    }

    public function testCannotCreateProductIdWithZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product ID must be greater than 0');
        new ProductId(0);
    }

    public function testCannotCreateProductIdWithNegativeNumber(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product ID must be greater than 0');
        new ProductId(-1);
    }
} 