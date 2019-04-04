<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

class ProjectsTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use TestCaseTrait;

    /**
     * @requires extension blackfire
     * @group blackfire
     */
    public function testGetSeries()
    {
        $client = static::createClient();
        $config = new Profile\Configuration();
        $config->assert('metrics.sql.queries.count < 1', 'SQL queries');
//         $this->expectException(ClientExceptionInterface::class);
//         $this->expectExceptionCode(400); // HTTP status code
//         $this->expectExceptionMessage(<<<ERROR
// users: This collection should contain 1 element or more.
// users: The current logged in user must be part of the users owning this resource.
// ERROR
// );

        $profile = $this->assertBlackfire($config, function () use ($client) {
            $response = $client->request('GET', '/api/tv_series');
            dump($response->getContent());
        });
    }
}
