<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\Service;

use App\FinancialProducts\Domain\Entity\Bank;
use App\FinancialProducts\Domain\Repository\BankRepositoryInterface;
use App\FinancialProducts\Domain\ValueObject\BankId;
use App\FinancialProducts\Domain\ValueObject\BankName;
use Doctrine\ORM\EntityManagerInterface;

class BankService
{
    public function __construct(
        private readonly BankRepositoryInterface $bankRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function findOrCreateBank(BankId $bankId, BankName $bankName): Bank
    {
        $existingBank = $this->bankRepository->findByExternalId($bankId->getValue());
        
        if ($existingBank === null) {
            $bank = new Bank($bankId, $bankName);
            $this->entityManager->persist($bank);
            return $bank;
        }

        // Update the existing bank with the new information
        $existingBank->updateFrom(new Bank($bankId, $bankName));
        return $existingBank;
    }
} 