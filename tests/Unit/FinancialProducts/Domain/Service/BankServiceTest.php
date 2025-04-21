<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Domain\Service;

use App\FinancialProducts\Domain\Entity\Bank;
use App\FinancialProducts\Domain\Repository\BankRepositoryInterface;
use App\FinancialProducts\Domain\Service\BankService;
use App\FinancialProducts\Domain\ValueObject\BankId;
use App\FinancialProducts\Domain\ValueObject\BankName;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Driver\Exception as DriverException;

class BankServiceTest extends Unit
{
    private BankService $bankService;
    private BankRepositoryInterface $bankRepository;
    private EntityManagerInterface $entityManager;

    protected function _before(): void
    {
        $this->bankRepository = $this->createMock(BankRepositoryInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->bankService = new BankService($this->bankRepository, $this->entityManager);
    }

    public function testCreateNewBank(): void
    {
        $bankId = new BankId(123);
        $bankName = new BankName('Nuevo Banco');

        $this->bankRepository->expects($this->once())
            ->method('findByExternalId')
            ->with(123)
            ->willReturn(null);

        $this->entityManager->expects($this->once())
            ->method('persist');

        $bank = $this->bankService->findOrCreateBank($bankId, $bankName);

        $this->assertInstanceOf(Bank::class, $bank);
        $this->assertEquals(123, $bank->getExternalBankId());
        $this->assertEquals('Nuevo Banco', $bank->getName());
    }

    public function testUpdateExistingBank(): void
    {
        $bankId = new BankId(123);
        $bankName = new BankName('Banco Actualizado');
        
        $existingBank = new Bank(new BankId(123), new BankName('Banco Antiguo'));
        
        $this->bankRepository->expects($this->once())
            ->method('findByExternalId')
            ->with(123)
            ->willReturn($existingBank);

        $bank = $this->bankService->findOrCreateBank($bankId, $bankName);

        $this->assertSame($existingBank, $bank);
        $this->assertEquals('Banco Actualizado', $bank->getName());
    }

    public function testHandleUniqueConstraintViolation(): void
    {
        $bankId = new BankId(2554);
        $bankName = new BankName('Banco de Prueba');

        $this->bankRepository->expects($this->once())
            ->method('findByExternalId')
            ->with(2554)
            ->willReturn(null);

        $driverException = $this->createMock(DriverException::class);
        $uniqueConstraintException = new UniqueConstraintViolationException(
            $driverException,
            null
        );

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->willThrowException($uniqueConstraintException);

        $this->expectException(UniqueConstraintViolationException::class);

        $this->bankService->findOrCreateBank($bankId, $bankName);
    }
} 