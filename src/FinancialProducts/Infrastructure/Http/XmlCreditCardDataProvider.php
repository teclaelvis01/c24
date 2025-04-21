<?php

declare(strict_types=1);

namespace App\FinancialProducts\Infrastructure\Http;

use App\FinancialProducts\Application\Mapper\CreditCardMapperInterface;
use App\FinancialProducts\Domain\Interfaces\CreditCardDataProviderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class XmlCreditCardDataProvider implements CreditCardDataProviderInterface
{
    private const API_URL = 'https://tools.financeads.net/webservice.php';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly CreditCardMapperInterface $creditCardMapper
    ) {}

    public function fetchCreditCards(): array
    {
        $response = $this->httpClient->request('GET', self::API_URL, [
            'query' => [
                'wf' => '1',
                'format' => 'xml',
                'calc' => 'kreditkarterechner',
                'country' => 'ES'
            ]
        ]);

        
        $content = $response->getContent();
        if (!str_starts_with(trim($content), '<?xml')) {
            throw new \RuntimeException('Response is not a valid XML document');
        }
        
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($content);
        if ($xml === false) {
            throw new \RuntimeException('Failed to parse XML response');
        }

        return $this->mapXmlToCreditCards($xml);
    }

    private function mapXmlToCreditCards(\SimpleXMLElement $xml): array
    {
        $creditCards = [];

        foreach ($xml->product as $product) {
            $creditCards[] = $this->creditCardMapper->fromXml($product);
        }

        return $creditCards;
    }
} 