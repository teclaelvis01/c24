<?php

declare(strict_types=1);

namespace App\FinancialProducts\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use App\FinancialProducts\Domain\Repository\CreditCardManualEditRepositoryInterface;
use App\FinancialProducts\Domain\Entity\CreditCardManualEdit;
use App\FinancialProducts\Domain\Entity\CreditCard;

class DoctrineCreditCardManualEditRepository implements CreditCardManualEditRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function findByCreditCard(CreditCard $creditCard): ?CreditCardManualEdit
    {
        return $this->entityManager->getRepository(CreditCardManualEdit::class)
            ->findOneBy(['creditCard' => $creditCard]);
    }

    public function save(CreditCardManualEdit $manualEdit): void
    {
        $this->entityManager->persist($manualEdit);
        $this->entityManager->flush();
    }
} 