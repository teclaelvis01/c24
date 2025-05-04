<?php

declare(strict_types=1);

namespace App\FinancialProducts\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use App\FinancialProducts\Domain\Repository\CreditCardRepositoryInterface;
use App\FinancialProducts\Domain\Repository\CreditCardPaginatedRepositoryInterface;
use App\FinancialProducts\Domain\Entity\CreditCard;
use App\FinancialProducts\Domain\Entity\Bank;
use App\FinancialProducts\Domain\Entity\CreditCardManualEdit;

class DoctrineCreditCardRepository implements CreditCardRepositoryInterface, CreditCardPaginatedRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function findById(int $id): ?CreditCard
    {
        return $this->entityManager->getRepository(CreditCard::class)->find($id);
    }

    public function save(CreditCard $creditCard): void
    {
        $this->entityManager->persist($creditCard);
        $this->entityManager->flush();
    }

    public function saveAll(array $creditCards): void
    {
        foreach ($creditCards as $creditCard) {
            $this->entityManager->persist($creditCard);
        }
        $this->entityManager->flush();
    }

    public function findByBank(Bank $bank): array
    {
        return $this->entityManager->getRepository(CreditCard::class)
            ->findBy(['bank' => $bank]);
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(CreditCard::class)
            ->findAll();
    }

    public function findByExternalProductId(string $externalProductId): ?CreditCard
    {
        return $this->entityManager->getRepository(CreditCard::class)
            ->findOneBy(['externalProductId' => $externalProductId]);
    }

    public function findPaginated(int $page = 1, int $limit = 10, string $sortBy = 'title', string $sortOrder = 'asc'): array
    {
        $offset = ($page - 1) * $limit;
        
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('c')
           ->from(CreditCard::class, 'c');
           
        // validate and normalize sort order
        $sortOrder = strtolower($sortOrder) === 'desc' ? 'DESC' : 'ASC';
        
        switch ($sortBy) {
            case 'title':
                $qb->orderBy('c.title', $sortOrder);
                break;
            case 'cost':
                $qb->orderBy('c.cost.amount', $sortOrder);
                break;
            default:
                $qb->orderBy('c.title', 'ASC');
                break;
        }
        
        $qb->setFirstResult($offset)
           ->setMaxResults($limit);
           
        return $qb->getQuery()->getResult();
    }
    
    public function countAll(): int
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('COUNT(c.id)')
           ->from(CreditCard::class, 'c');
           
        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function findLatestManualEdit(CreditCard $creditCard): ?CreditCardManualEdit
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('e')
           ->from(CreditCardManualEdit::class, 'e')
           ->where('e.creditCard = :creditCard')
           ->orderBy('e.updatedAt', 'DESC')
           ->setMaxResults(1)
           ->setParameter('creditCard', $creditCard);
           
        return $qb->getQuery()->getOneOrNullResult();
    }
} 