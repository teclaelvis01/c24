<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\Entity;

use App\FinancialProducts\Domain\ValueObject\BankId;
use App\FinancialProducts\Domain\ValueObject\BankName;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\FinancialProducts\Domain\Entity\CreditCard;

#[ORM\Entity]
#[ORM\Table(name: 'banks')]
class Bank
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(type: 'integer', unique: true)]
    private int $externalBankId;

    #[ORM\Column(type: 'string', length: 500)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'bank', targetEntity: CreditCard::class)]
    private Collection $creditCards;

    public function __construct(BankId $bankId, BankName $name)
    {
        $this->externalBankId = $bankId->getValue();
        $this->name = $name->getValue();
        $this->creditCards = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getExternalBankId(): int
    {
        return $this->externalBankId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCreditCards(): Collection
    {
        return $this->creditCards;
    }

    public function updateFrom(Bank $otherBank): void
    {
        $this->name = $otherBank->getName();
    }


} 