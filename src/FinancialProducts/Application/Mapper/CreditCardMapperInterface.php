<?php

declare(strict_types=1);

namespace App\FinancialProducts\Application\Mapper;

use App\FinancialProducts\Domain\Entity\CreditCard;
use SimpleXMLElement;

interface CreditCardMapperInterface
{
    public function fromXml(SimpleXMLElement $xml): CreditCard;
} 