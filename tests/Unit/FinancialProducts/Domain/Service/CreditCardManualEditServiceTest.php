<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Domain\Service;

use App\FinancialProducts\Domain\Entity\CreditCard;
use App\FinancialProducts\Domain\Entity\CreditCardManualEdit;
use App\FinancialProducts\Domain\Repository\CreditCardManualEditRepositoryInterface;
use App\FinancialProducts\Domain\Service\CreditCardManualEditService;
use App\FinancialProducts\Domain\ValueObject\Money;
use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;

class CreditCardManualEditServiceTest extends Unit
{
    private CreditCardManualEditRepositoryInterface|MockObject $manualEditRepository;
    private CreditCardManualEditService $manualEditService;

    protected function setUp(): void
    {
        $this->manualEditRepository = $this->createMock(CreditCardManualEditRepositoryInterface::class);
        $this->manualEditService = new CreditCardManualEditService($this->manualEditRepository);
    }

    public function testEditCreditCardCreatesNewManualEdit(): void
    {
        $creditCard = $this->createMock(CreditCard::class);
        $newTitle = 'Nuevo Título';
        $newDescription = 'Nueva Descripción';
        $newIncentiveAmount = new Money(100.50);
        $newCost = new Money(50.00);

        $this->manualEditRepository->expects($this->once())
            ->method('findByCreditCard')
            ->with($creditCard)
            ->willReturn(null);

        $this->manualEditRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($manualEdit) use ($creditCard, $newTitle, $newDescription, $newIncentiveAmount, $newCost) {
                return $manualEdit instanceof CreditCardManualEdit
                    && $manualEdit->getCreditCard() === $creditCard
                    && $manualEdit->getTitle() === $newTitle
                    && $manualEdit->getDescription() === $newDescription
                    && $manualEdit->getIncentiveAmount() === $newIncentiveAmount
                    && $manualEdit->getCost() === $newCost;
            }));

        $this->manualEditService->editCreditCard(
            $creditCard,
            $newTitle,
            $newDescription,
            $newIncentiveAmount,
            $newCost
        );
    }

    public function testEditCreditCardUpdatesExistingManualEdit(): void
    {
        $creditCard = $this->createMock(CreditCard::class);
        $existingManualEdit = $this->createMock(CreditCardManualEdit::class);
        $newTitle = 'Nuevo Título';
        $newDescription = 'Nueva Descripción';
        $newIncentiveAmount = new Money(100.50);
        $newCost = new Money(50.00);

        $this->manualEditRepository->expects($this->once())
            ->method('findByCreditCard')
            ->with($creditCard)
            ->willReturn($existingManualEdit);

        $existingManualEdit->expects($this->once())
            ->method('setTitle')
            ->with($newTitle);

        $existingManualEdit->expects($this->once())
            ->method('setDescription')
            ->with($newDescription);

        $existingManualEdit->expects($this->once())
            ->method('setIncentiveAmount')
            ->with($newIncentiveAmount);

        $existingManualEdit->expects($this->once())
            ->method('setCost')
            ->with($newCost);

        $this->manualEditRepository->expects($this->once())
            ->method('save')
            ->with($existingManualEdit);

        $this->manualEditService->editCreditCard(
            $creditCard,
            $newTitle,
            $newDescription,
            $newIncentiveAmount,
            $newCost
        );
    }

    public function testGetCombinedDataReturnsOriginalDataWhenNoManualEdit(): void
    {
        $creditCard = $this->createMock(CreditCard::class);
        $originalTitle = 'Título Original';
        $originalDescription = 'Descripción Original';
        $originalIncentiveAmount = new Money(200.00);
        $originalCost = new Money(100.00);

        $creditCard->method('getTitle')->willReturn($originalTitle);
        $creditCard->method('getDescription')->willReturn($originalDescription);
        $creditCard->method('getIncentiveAmount')->willReturn($originalIncentiveAmount);
        $creditCard->method('getCost')->willReturn($originalCost);

        $this->manualEditRepository->expects($this->once())
            ->method('findByCreditCard')
            ->with($creditCard)
            ->willReturn(null);

        $combinedData = $this->manualEditService->getCombinedData($creditCard);

        $this->assertEquals($originalTitle, $combinedData['title']);
        $this->assertEquals($originalDescription, $combinedData['description']);
        $this->assertEquals($originalIncentiveAmount, $combinedData['incentiveAmount']);
        $this->assertEquals($originalCost, $combinedData['cost']);
    }

    public function testGetCombinedDataReturnsManualEditDataWhenAvailable(): void
    {
        $creditCard = $this->createMock(CreditCard::class);
        $manualEdit = $this->createMock(CreditCardManualEdit::class);
        $manualTitle = 'Título Manual';
        $manualDescription = 'Descripción Manual';
        $manualIncentiveAmount = new Money(150.00);
        $manualCost = new Money(75.00);

        $manualEdit->method('getTitle')->willReturn($manualTitle);
        $manualEdit->method('getDescription')->willReturn($manualDescription);
        $manualEdit->method('getIncentiveAmount')->willReturn($manualIncentiveAmount);
        $manualEdit->method('getCost')->willReturn($manualCost);

        $this->manualEditRepository->expects($this->once())
            ->method('findByCreditCard')
            ->with($creditCard)
            ->willReturn($manualEdit);

        $combinedData = $this->manualEditService->getCombinedData($creditCard);

        $this->assertEquals($manualTitle, $combinedData['title']);
        $this->assertEquals($manualDescription, $combinedData['description']);
        $this->assertEquals($manualIncentiveAmount, $combinedData['incentiveAmount']);
        $this->assertEquals($manualCost, $combinedData['cost']);
    }

    public function testGetCombinedDataReturnsMixedDataWhenSomeManualEditsExist(): void
    {
        $creditCard = $this->createMock(CreditCard::class);
        $manualEdit = $this->createMock(CreditCardManualEdit::class);
        $originalTitle = 'Título Original';
        $manualDescription = 'Descripción Manual';
        $originalIncentiveAmount = new Money(200.00);
        $manualCost = new Money(75.00);

        $creditCard->method('getTitle')->willReturn($originalTitle);
        $creditCard->method('getIncentiveAmount')->willReturn($originalIncentiveAmount);

        $manualEdit->method('getTitle')->willReturn(null);
        $manualEdit->method('getDescription')->willReturn($manualDescription);
        $manualEdit->method('getIncentiveAmount')->willReturn(null);
        $manualEdit->method('getCost')->willReturn($manualCost);

        $this->manualEditRepository->expects($this->once())
            ->method('findByCreditCard')
            ->with($creditCard)
            ->willReturn($manualEdit);

        $combinedData = $this->manualEditService->getCombinedData($creditCard);

        $this->assertEquals($originalTitle, $combinedData['title']);
        $this->assertEquals($manualDescription, $combinedData['description']);
        $this->assertEquals($originalIncentiveAmount, $combinedData['incentiveAmount']);
        $this->assertEquals($manualCost, $combinedData['cost']);
    }
} 