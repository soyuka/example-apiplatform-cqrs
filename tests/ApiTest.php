<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Blackfire\Bridge\PhpUnit\TestCaseTrait;
use Blackfire\Profile;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Blackfire\Bridge\PhpUnit\TestConstraint;

class ProjectsTest extends ApiTestCase
{
    use TestCaseTrait;
    use ReloadDatabaseTrait;

    /**
     * @dataProvider rateableResources
     */
    public function testRate(string $resource)
    {
        $client = static::createClient();
        $this->runRate($resource, $client);
    }

    /**
     * @requires extension blackfire
     * @group blackfire
     * @dataProvider rateableResources
     */
    public function testProfileRate(string $resource)
    {
        $client = static::createClient();
        $config = new Profile\Configuration();
        $config->setTitle('Rate queries');
        $config->assert('metrics.doctrine.orm.flush.count == 2', 'Commit entities');
        $self = $this;
        $profile = $this->assertBlackfire($config, function () use ($resource, $client, $self) {
            $self->runRate($resource, $client);
        });

        $this->outputProfileUrl($profile);
    }

    /**
     * @requires extension blackfire
     * @group blackfire
     */
    public function testProfileRateTiming()
    {
        $samples = 10;
        $client = static::createClient();
        $config = new Profile\Configuration();
        $config->setTitle('Rate timing');
        $config->assert('main.wall_time < 100ms');
        $config->assert('percent(main.wall_time) < 10%', 'Time diff with previous profile');
        $config->assert('percent(main.peak_memory) < 10%', 'Memory diff with previous profile');
        $config->setSamples($samples);

        $response = $client->request('GET', '/api/episodes');
        $content = (array) json_decode($response->getContent());
        $item = $content['hydra:member'][0];

		$probe = self::$blackfire->createProbe($config, false);

        for ($i = 1; $i <= $samples; $i++) {
            $probe->enable();
            $response = $client->request('PUT', $item->{'@id'}.'/rate', [
                'json' => [
                    'note' => 5
                ]
            ]);
            $this->assertSame(200, $response->getStatusCode());
            $probe->close();
        }

        $profile = self::$blackfire->endProbe($probe);
        $this->assertThat($profile, new TestConstraint());
        $this->outputProfileUrl($profile);
    }


    public function rateableResources()
    {
        return [
            ['tv_series'],
            ['tv_seasons'],
            ['episodes']
        ];
    }

    private function runRate(string $resource, $client)
    {
        $response = $client->request('GET', '/api/'.$resource);
        $this->assertSame(200, $response->getStatusCode());
        $content = (array) json_decode($response->getContent());
        $this->assertLessThanOrEqual(30, count($content['hydra:member']));
        $item = $content['hydra:member'][0];
        $response = $client->request('GET', $item->{'@id'});
        $this->assertSame(200, $response->getStatusCode());
        $content = json_decode($response->getContent());
        $this->assertNull($content->aggregateRating);

        $response = $client->request('PUT', $item->{'@id'}.'/rate', [
            'json' => [
                'note' => 5
            ]
        ]);

        $this->assertSame(200, $response->getStatusCode());
        $content = json_decode($response->getContent());
        $this->assertEquals(5, $content->aggregateRating->ratingCount);
        $this->assertEquals(1, $content->aggregateRating->reviewCount);

        $response = $client->request('PUT', $item->{'@id'}.'/rate', [
            'json' => [
                'note' => 2
            ]
        ]);

        $this->assertSame(200, $response->getStatusCode());
        $content = json_decode($response->getContent());
        $this->assertEquals(7, $content->aggregateRating->ratingCount);
        $this->assertEquals(2, $content->aggregateRating->reviewCount);
    }

    private function outputProfileUrl($profile) {
        echo PHP_EOL."Blackfire url: {$profile->getURL()}".PHP_EOL;
    }
}
