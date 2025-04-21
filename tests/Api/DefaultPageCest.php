<?php

declare(strict_types=1);


namespace App\Tests\Api;

use Tests\ApiTester;

final class DefaultPageCest
{
    public function _before(ApiTester $I): void
    {
        // Code here will be executed before each test.
    }

    public function testDefaultPage(ApiTester $I): void
    {
        $I->sendGet('/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'version' => 'string',
            'name' => 'string',
            'environment' => 'string'
        ]);
    }
}
