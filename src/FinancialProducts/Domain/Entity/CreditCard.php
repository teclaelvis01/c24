<?php

declare(strict_types=1);

namespace App\FinancialProducts\Domain\Entity;

use App\FinancialProducts\Domain\ValueObject\CardType;
use App\FinancialProducts\Domain\ValueObject\CardTypeEnum;
use App\FinancialProducts\Domain\ValueObject\Cost;
use App\FinancialProducts\Domain\ValueObject\Money;
use App\FinancialProducts\Domain\ValueObject\ProductDeepLink;
use App\FinancialProducts\Domain\ValueObject\ProductExtraInfo;
use App\FinancialProducts\Domain\ValueObject\ProductFirstYearFee;
use App\FinancialProducts\Domain\ValueObject\ProductId;
use App\FinancialProducts\Domain\ValueObject\ProductIncentiveAmount;
use App\FinancialProducts\Domain\ValueObject\ProductLogoUrl;
use App\FinancialProducts\Domain\ValueObject\ProductTitle;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'credit_cards')]
class CreditCard
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(type: 'string', unique: true)]
    private string $externalProductId;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'string', length: 10)]
    private string $type;

    #[ORM\Column(type: 'string', length: 500)]
    private string $description;

    #[ORM\Column(type: 'string', length: 255)]
    private string $logoUrl;

    #[ORM\Column(type: 'string', length: 500)]
    private string $deepLink;

    #[ORM\Embedded(class: Money::class, columnPrefix: 'first_year_fee_')]
    private Money $firstYearFee;

    #[ORM\Embedded(class: Money::class, columnPrefix: 'incentive_')]
    private Money $incentiveAmount;

    #[ORM\Embedded(class: Money::class, columnPrefix: 'cost_')]
    private Money $cost;

    #[ORM\ManyToOne(targetEntity: Bank::class, inversedBy: 'creditCards', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'bank_id', referencedColumnName: 'id', nullable: false)]
    private Bank $bank;

    public function __construct(
        ProductId $productId,
        ProductTitle $title,
        CardType $type,
        ProductExtraInfo $description,
        ProductLogoUrl $logoUrl,
        ProductDeepLink $deepLink,
        ProductFirstYearFee $firstYearFee,
        ProductIncentiveAmount $incentiveAmount,
        Cost $cost,
        Bank $bank
    ) {
        $this->externalProductId = (string) $productId->getValue();
        $this->title = $title->getValue();
        $this->type = $type->getValue();
        $this->description = $description->getValue();
        $this->logoUrl = $logoUrl->getValue();
        $this->deepLink = $deepLink->getValue();
        $this->firstYearFee = $firstYearFee->getMoney();
        $this->incentiveAmount = $incentiveAmount->getMoney();
        $this->cost = $cost->getMoney();
        $this->bank = $bank;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getExternalProductId(): string
    {
        return $this->externalProductId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getType(): CardType
    {
        return new CardType($this->type);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getLogoUrl(): string
    {
        return $this->logoUrl;
    }

    public function getDeepLink(): string
    {
        return $this->deepLink;
    }

    public function getFirstYearFee(): Money
    {
        return $this->firstYearFee;
    }

    public function getIncentiveAmount(): Money
    {
        return $this->incentiveAmount;
    }

    public function getCost(): Money
    {
        return $this->cost;
    }

    public function getBank(): Bank
    {
        return $this->bank;
    }

    public function setBank(Bank $bank): void
    {
        if ($this->bank !== $bank) {
            $this->bank = $bank;
        }
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setIncentiveAmount(Money $incentiveAmount): void
    {
        $this->incentiveAmount = $incentiveAmount;
    }

    public function setCost(Money $cost): void
    {
        $this->cost = $cost;
    }
}
