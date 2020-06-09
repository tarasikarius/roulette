<?php

namespace App\Tests\Controller;

use App\Tests\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class StatisticControllerTest extends ApiTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testRequestIsSuccessfulForAdmin($url)
    {
        $this->adminAuthRequest('GET', $url);

        $this->assertResponseIsSuccessful();
    }

    /**
     * @dataProvider urlProvider
     */
    public function testRequestIsForbiddenForPlayer($url)
    {
        $this->playerAuthRequest('GET', $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function urlProvider()
    {
        yield ['/api/statistics/players'];
        yield ['/api/statistics/rounds'];
    }
}