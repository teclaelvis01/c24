<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\FinancialProducts\Domain\ValueObject\Money;

#[ORM\Entity]
#[ORM\Table(name: 'credit_card_manual_edits')]
class CreditCardManualEdit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: CreditCard::class)]
    #[ORM\JoinColumn(nullable: false)]
    private CreditCard $creditCard;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Embedded(class: Money::class, columnPrefix: 'incentive_amount_')]
    private ?Money $incentiveAmount = null;

    #[ORM\Embedded(class: Money::class, columnPrefix: 'cost_')]
    private ?Money $cost = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $updatedAt;

    public function __construct(CreditCard $creditCard)
    {
        $this->creditCard = $creditCard;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreditCard(): CreditCard
    {
        return $this->creditCard;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
        $this->updatedAt = new \DateTime();
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
        $this->updatedAt = new \DateTime();
    }

    public function getIncentiveAmount(): ?Money
    {
        return $this->incentiveAmount;
    }

    public function setIncentiveAmount(?Money $incentiveAmount): void
    {
        $this->incentiveAmount = $incentiveAmount;
        $this->updatedAt = new \DateTime();
    }

    public function getCost(): ?Money
    {
        return $this->cost;
    }

    public function setCost(?Money $cost): void
    {
        $this->cost = $cost;
        $this->updatedAt = new \DateTime();
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
} 