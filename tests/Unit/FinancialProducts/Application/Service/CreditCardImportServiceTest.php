<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Application\Service;

use App\FinancialProducts\Domain\Entity\CreditCard;
use App\FinancialProducts\Domain\Interfaces\CreditCardDataProviderInterface;
use App\FinancialProducts\Domain\Repository\CreditCardRepositoryInterface;
use App\FinancialProducts\Application\Service\CreditCardImportService;
use App\FinancialProducts\Domain\Service\BankService;
use App\FinancialProducts\Domain\Service\CreditCardManualEditService;
use App\FinancialProducts\Domain\ValueObject\Money;
use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use App\FinancialProducts\Domain\Entity\Bank;

class CreditCardImportServiceTest extends Unit
{
    private CreditCardRepositoryInterface|MockObject $creditCardRepository;
    private CreditCardDataProviderInterface|MockObject $creditCardDataProvider;
    private CreditCardManualEditService|MockObject $manualEditService;
    private CreditCardImportService $importService;
    private BankService|MockObject $bankService;

    protected function setUp(): void
    {
        $this->creditCardRepository = $this->createMock(CreditCardRepositoryInterface::class);
        $this->creditCardDataProvider = $this->createMock(CreditCardDataProviderInterface::class);
        $this->manualEditService = $this->createMock(CreditCardManualEditService::class);  
        $this->bankService = $this->createMock(BankService::class);
        $this->importService = new CreditCardImportService(
            $this->creditCardRepository,
            $this->creditCardDataProvider,
            $this->manualEditService,
            $this->bankService
        );
    }

    public function testImportNewCreditCard(): void
    {
        $bank = $this->createMock(Bank::class);
        $bank->method('getExternalBankId')->willReturn(456);
        $bank->method('getName')->willReturn('Test Bank');

        $creditCard = $this->createMock(CreditCard::class);
        $creditCard->method('getExternalProductId')->willReturn('123');
        $creditCard->method('getBank')->willReturn($bank);
        $creditCard->method('getTitle')->willReturn('Test Card');
        $creditCard->method('getType')->willReturn(new \App\FinancialProducts\Domain\ValueObject\CardType('credit'));
        $creditCard->method('getDescription')->willReturn('Test Description');
        $creditCard->method('getLogoUrl')->willReturn('http://test.com/logo.png');
        $creditCard->method('getDeepLink')->willReturn('http://test.com/card');
        $creditCard->method('getFirstYearFee')->willReturn(new Money(0));
        $creditCard->method('getIncentiveAmount')->willReturn(new Money(100));
        $creditCard->method('getCost')->willReturn(new Money(50));

        $this->creditCardDataProvider->expects($this->once())
            ->method('fetchCreditCards')
            ->willReturn([$creditCard]);

        $this->creditCardRepository->expects($this->once())
            ->method('findByExternalProductId')
            ->with('123')
            ->willReturn(null);

        $this->bankService->expects($this->once())
            ->method('findOrCreateBank')
            ->with(
                $this->callback(function($bankId) {
                    return $bankId->getValue() === 456;
                }),
                $this->callback(function($bankName) {
                    return $bankName->getValue() === 'Test Bank';
                })
            )
            ->willReturn($bank);

        $this->creditCardRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function($card) {
                return $card instanceof CreditCard;
            }));

        $this->manualEditService->expects($this->never())
            ->method('getCombinedData');

        $this->importService->importCreditCards();
    }

    public function testImportExistingCreditCardWithManualEdits(): void
    {
        $bank = $this->createMock(Bank::class);
        $bank->method('getExternalBankId')->willReturn(456);
        $bank->method('getName')->willReturn('Test Bank');

        $creditCard = $this->createMock(CreditCard::class);
        $creditCard->method('getExternalProductId')->willReturn('123');
        $creditCard->method('getBank')->willReturn($bank);
        $creditCard->method('getTitle')->willReturn('Test Card');
        $creditCard->method('getType')->willReturn(new \App\FinancialProducts\Domain\ValueObject\CardType('credit'));
        $creditCard->method('getDescription')->willReturn('Test Description');
        $creditCard->method('getLogoUrl')->willReturn('http://test.com/logo.png');
        $creditCard->method('getDeepLink')->willReturn('http://test.com/card');
        $creditCard->method('getFirstYearFee')->willReturn(new Money(0));
        $creditCard->method('getIncentiveAmount')->willReturn(new Money(100));
        $creditCard->method('getCost')->willReturn(new Money(50));

        $existingCard = $this->createMock(CreditCard::class);

        $this->creditCardDataProvider->expects($this->once())
            ->method('fetchCreditCards')
            ->willReturn([$creditCard]);

        $this->creditCardRepository->expects($this->once())
            ->method('findByExternalProductId')
            ->with('123')
            ->willReturn($existingCard);

        $this->bankService->expects($this->once())
            ->method('findOrCreateBank')
            ->with(
                $this->callback(function($bankId) {
                    return $bankId->getValue() === 456;
                }),
                $this->callback(function($bankName) {
                    return $bankName->getValue() === 'Test Bank';
                })
            )
            ->willReturn($bank);

        $combinedData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'incentiveAmount' => new Money(100.50),
            'cost' => new Money(50.00)
        ];

        $this->manualEditService->expects($this->once())
            ->method('getCombinedData')
            ->with($existingCard)
            ->willReturn($combinedData);

        $existingCard->expects($this->once())
            ->method('setBank')
            ->with($bank);

        $existingCard->expects($this->once())
            ->method('setTitle')
            ->with('Updated Title');

        $existingCard->expects($this->once())
            ->method('setDescription')
            ->with('Updated Description');

        $existingCard->expects($this->once())
            ->method('setIncentiveAmount')
            ->with($combinedData['incentiveAmount']);

        $existingCard->expects($this->once())
            ->method('setCost')
            ->with($combinedData['cost']);

        $this->creditCardRepository->expects($this->once())
            ->method('save')
            ->with($existingCard);

        $this->importService->importCreditCards();
    }

    public function testImportMultipleCreditCards(): void
    {
        $bank1 = $this->createMock(Bank::class);
        $bank1->method('getExternalBankId')->willReturn(456);
        $bank1->method('getName')->willReturn('Test Bank 1');

        $bank2 = $this->createMock(Bank::class);
        $bank2->method('getExternalBankId')->willReturn(789);
        $bank2->method('getName')->willReturn('Test Bank 2');

        $creditCard1 = $this->createMock(CreditCard::class);
        $creditCard1->method('getExternalProductId')->willReturn('123');
        $creditCard1->method('getBank')->willReturn($bank1);
        $creditCard1->method('getTitle')->willReturn('Test Card 1');
        $creditCard1->method('getType')->willReturn(new \App\FinancialProducts\Domain\ValueObject\CardType('credit'));
        $creditCard1->method('getDescription')->willReturn('Test Description 1');
        $creditCard1->method('getLogoUrl')->willReturn('http://test.com/logo1.png');
        $creditCard1->method('getDeepLink')->willReturn('http://test.com/card1');
        $creditCard1->method('getFirstYearFee')->willReturn(new Money(0));
        $creditCard1->method('getIncentiveAmount')->willReturn(new Money(100));
        $creditCard1->method('getCost')->willReturn(new Money(50));

        $creditCard2 = $this->createMock(CreditCard::class);
        $creditCard2->method('getExternalProductId')->willReturn('456');
        $creditCard2->method('getBank')->willReturn($bank2);
        $creditCard2->method('getTitle')->willReturn('Test Card 2');
        $creditCard2->method('getType')->willReturn(new \App\FinancialProducts\Domain\ValueObject\CardType('credit'));
        $creditCard2->method('getDescription')->willReturn('Test Description 2');
        $creditCard2->method('getLogoUrl')->willReturn('http://test.com/logo2.png');
        $creditCard2->method('getDeepLink')->willReturn('http://test.com/card2');
        $creditCard2->method('getFirstYearFee')->willReturn(new Money(0));
        $creditCard2->method('getIncentiveAmount')->willReturn(new Money(200));
        $creditCard2->method('getCost')->willReturn(new Money(75));

        $existingCard = $this->createMock(CreditCard::class);

        $this->creditCardDataProvider->expects($this->once())
            ->method('fetchCreditCards')
            ->willReturn([$creditCard1, $creditCard2]);

        $this->creditCardRepository->expects($this->exactly(2))
            ->method('findByExternalProductId')
            ->willReturnCallback(function ($id) use ($existingCard) {
                return $id === '123' ? null : $existingCard;
            });

        $this->bankService->expects($this->exactly(2))
            ->method('findOrCreateBank')
            ->willReturnCallback(function($bankId, $bankName) use ($bank1, $bank2) {
                return $bankId->getValue() === 456 ? $bank1 : $bank2;
            });

        $combinedData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'incentiveAmount' => new Money(100.50),
            'cost' => new Money(50.00)
        ];

        $this->manualEditService->expects($this->once())
            ->method('getCombinedData')
            ->with($existingCard)
            ->willReturn($combinedData);

        $existingCard->expects($this->once())
            ->method('setBank')
            ->with($bank2);

        $existingCard->expects($this->once())
            ->method('setTitle')
            ->with('Updated Title');

        $existingCard->expects($this->once())
            ->method('setDescription')
            ->with('Updated Description');

        $existingCard->expects($this->once())
            ->method('setIncentiveAmount')
            ->with($combinedData['incentiveAmount']);

        $existingCard->expects($this->once())
            ->method('setCost')
            ->with($combinedData['cost']);

        $this->creditCardRepository->expects($this->exactly(2))
            ->method('save')
            ->willReturnCallback(function($card) use ($existingCard) {
                static $callCount = 0;
                $callCount++;
                
                if ($callCount === 1) {
                    $this->assertInstanceOf(CreditCard::class, $card);
                } else {
                    $this->assertSame($existingCard, $card);
                }
                
                return null;
            });

        $this->importService->importCreditCards();
    }
} 