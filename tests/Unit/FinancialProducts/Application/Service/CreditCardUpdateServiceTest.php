<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Application\Service;

use App\FinancialProducts\Application\Service\CreditCardUpdateService;
use App\FinancialProducts\Domain\Entity\CreditCard;
use App\FinancialProducts\Domain\Repository\CreditCardRepositoryInterface;
use App\FinancialProducts\Domain\Service\CreditCardManualEditService;
use App\FinancialProducts\Domain\ValueObject\Money;
use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;

class CreditCardUpdateServiceTest extends Unit
{
    private CreditCardRepositoryInterface|MockObject $creditCardRepository;
    private CreditCardManualEditService|MockObject $manualEditService;
    private CreditCardUpdateService $updateService;

    protected function setUp(): void
    {
        $this->creditCardRepository = $this->createMock(CreditCardRepositoryInterface::class);
        $this->manualEditService = $this->createMock(CreditCardManualEditService::class);
        $this->updateService = new CreditCardUpdateService(
            $this->creditCardRepository,
            $this->manualEditService
        );
    }

    public function testUpdateCardWithAllFields(): void
    {
        $creditCard = $this->createMock(CreditCard::class);
        $newTitle = 'Nuevo Título';
        $newDescription = 'Nueva Descripción';
        $newIncentiveAmount = ['amount' => 100.50, 'currencyCode' => 'EUR'];
        $newCost = ['amount' => 50.00, 'currencyCode' => 'EUR'];

        $this->creditCardRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($creditCard);

        $this->manualEditService->expects($this->once())
            ->method('editCreditCard')
            ->with(
                $creditCard,
                $newTitle,
                $newDescription,
                $this->callback(function($money) use ($newIncentiveAmount) {
                    return $money instanceof Money
                        && $money->getAmount() === $newIncentiveAmount['amount']
                        && $money->getCurrency()->getCode() === $newIncentiveAmount['currencyCode'];
                }),
                $this->callback(function($money) use ($newCost) {
                    return $money instanceof Money
                        && $money->getAmount() === $newCost['amount']
                        && $money->getCurrency()->getCode() === $newCost['currencyCode'];
                })
            );

        $this->updateService->updateCard(
            1,
            $newTitle,
            $newDescription,
            $newIncentiveAmount,
            $newCost
        );
    }

    public function testUpdateCardWithPartialFields(): void
    {
        $creditCard = $this->createMock(CreditCard::class);
        $newTitle = 'Nuevo Título';

        $this->creditCardRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($creditCard);

        $this->manualEditService->expects($this->once())
            ->method('editCreditCard')
            ->with(
                $creditCard,
                $newTitle,
                null,
                null,
                null
            );

        $this->updateService->updateCard(
            1,
            $newTitle
        );
    }

    public function testUpdateCardNotFound(): void
    {
        $this->creditCardRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(null);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Credit card not found');

        $this->updateService->updateCard(1);
    }
} 