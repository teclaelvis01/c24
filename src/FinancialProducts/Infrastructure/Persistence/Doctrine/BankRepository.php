<?php

declare(strict_types=1);

namespace App\FinancialProducts\Infrastructure\Persistence\Doctrine;

use App\FinancialProducts\Domain\Entity\Bank;
use App\FinancialProducts\Domain\Repository\BankRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class BankRepository implements BankRepositoryInterface
{
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Bank::class);
    }

    public function findByExternalId(int $externalId): ?Bank
    {
        return $this->repository->findOneBy(['externalBankId' => $externalId]);
    }
} 