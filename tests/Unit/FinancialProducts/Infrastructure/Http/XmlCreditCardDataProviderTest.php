<?php

declare(strict_types=1);

namespace Tests\Unit\FinancialProducts\Infrastructure\Http;

use App\FinancialProducts\Application\Mapper\CreditCardMapperInterface;
use App\FinancialProducts\Domain\Entity\CreditCard;
use App\FinancialProducts\Infrastructure\Http\XmlCreditCardDataProvider;
use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class XmlCreditCardDataProviderTest extends Unit
{
    private HttpClientInterface|MockObject $httpClient;
    private CreditCardMapperInterface|MockObject $creditCardMapper;
    private XmlCreditCardDataProvider $dataProvider;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->creditCardMapper = $this->createMock(CreditCardMapperInterface::class);
        $this->dataProvider = new XmlCreditCardDataProvider($this->httpClient, $this->creditCardMapper);
    }

    public function testFetchCreditCardsSuccessfully(): void
    {
        $xmlResponse = <<<XML
        <?xml version="1.0" encoding="UTF-8"?>
        <products>
            <product>
                <productid>1</productid>
                <bankid>1</bankid>
                <bank>Test Bank</bank>
                <produkt>Test Card</produkt>
                <cardtype_text>credit</cardtype_text>
                <anmerkungen>Test description</anmerkungen>
                <logo>https://example.com/logo.png</logo>
                <link>https://example.com/product/1</link>
                <gebuehrenjahr1>100,50</gebuehrenjahr1>
                <incentive_amount>50,00</incentive_amount>
                <kosten>10,00</kosten>
            </product>
        </products>
        XML;

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')->willReturn($xmlResponse);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', 'https://tools.financeads.net/webservice.php', [
                'query' => [
                    'wf' => '1',
                    'format' => 'xml',
                    'calc' => 'kreditkarterechner',
                    'country' => 'ES'
                ]
            ])
            ->willReturn($response);

        $creditCard = $this->createMock(CreditCard::class);
        $this->creditCardMapper->expects($this->once())
            ->method('fromXml')
            ->willReturn($creditCard);

        $result = $this->dataProvider->fetchCreditCards();
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertSame($creditCard, $result[0]);
    }

    public function testFetchCreditCardsWithInvalidXml(): void
    {
        $invalidXml = 'Invalid XML content';

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')->willReturn($invalidXml);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->willReturn($response);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Response is not a valid XML document');

        $this->dataProvider->fetchCreditCards();
    }

    public function testFetchCreditCardsFailedToParseXml(): void
    {
        $invalidXml = "<?xml version='1.0' encoding='UTF-8' ?><root><tag></root>";

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')->willReturn($invalidXml);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->willReturn($response);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Failed to parse XML response');

        $this->dataProvider->fetchCreditCards();
    }

    public function testFetchCreditCardsWithEmptyResponse(): void
    {
        $emptyXml = '<?xml version="1.0" encoding="UTF-8"?><products></products>';

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')->willReturn($emptyXml);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->willReturn($response);

        $result = $this->dataProvider->fetchCreditCards();
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
} 