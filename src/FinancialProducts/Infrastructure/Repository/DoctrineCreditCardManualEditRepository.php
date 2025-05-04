<?php

declare(strict_types=1);

namespace App\FinancialProducts\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use App\FinancialProducts\Domain\Repository\CreditCardManualEditRepositoryInterface;
use App\FinancialProducts\Domain\Repository\CreditCardPaginatedRepositoryInterface;
use App\FinancialProducts\Domain\Entity\CreditCardManualEdit;
use App\FinancialProducts\Domain\Entity\CreditCard;

class DoctrineCreditCardManualEditRepository implements CreditCardManualEditRepositoryInterface, CreditCardPaginatedRepositoryInterface
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

    public function findPaginated(int $page = 1, int $limit = 10, string $sortBy = 'title', string $sortOrder = 'asc'): array
    {
        $offset = ($page - 1) * $limit;
        
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('me')
           ->from(CreditCardManualEdit::class, 'me')
           ->join('me.creditCard', 'c');
           
        // validate and normalize sort order
        $sortOrder = strtolower($sortOrder) === 'desc' ? 'DESC' : 'ASC';
        
        switch ($sortBy) {
            case 'title':
                $qb->orderBy('me.title', $sortOrder);
                break;
            case 'cost':
                $qb->orderBy('me.cost.amount', $sortOrder);
                break;
            default:
                $qb->orderBy('me.title', 'ASC');
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
           ->from(CreditCardManualEdit::class, 'c');
           
        return (int) $qb->getQuery()->getSingleScalarResult();
    } 
} 