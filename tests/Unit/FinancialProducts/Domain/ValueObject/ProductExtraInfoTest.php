<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Domain\ValueObject;

use App\FinancialProducts\Domain\ValueObject\ProductExtraInfo;
use Codeception\Test\Unit;

class ProductExtraInfoTest extends Unit
{
    public function testCanCreateValidProductExtraInfo(): void
    {
        $extraInfo = new ProductExtraInfo('Some extra information');
        $this->assertEquals('Some extra information', $extraInfo->getValue());
    }

    public function testCanCreateNullProductExtraInfo(): void
    {
        $extraInfo = new ProductExtraInfo();
        $this->assertNull($extraInfo->getValue());
    }

    public function testCannotCreateProductExtraInfoWithTooLongString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Extra info cannot be longer than 500 characters');
        new ProductExtraInfo(str_repeat('a', 501));
    }

    public function testEqualsMethod(): void
    {
        $extraInfo1 = new ProductExtraInfo('Same info');
        $extraInfo2 = new ProductExtraInfo('Same info');
        $extraInfo3 = new ProductExtraInfo('Different info');

        $this->assertTrue($extraInfo1->equals($extraInfo2));
        $this->assertFalse($extraInfo1->equals($extraInfo3));
    }

    public function testToStringReturnsValueOrEmptyString(): void
    {
        $extraInfo1 = new ProductExtraInfo('Some info');
        $extraInfo2 = new ProductExtraInfo();

        $this->assertEquals('Some info', (string) $extraInfo1);
        $this->assertEquals('', (string) $extraInfo2);
    }
} 