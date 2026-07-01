<?php

namespace App\Tests\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    /**
     * @return array<string, mixed>
     */
    private function decodeJson(string $json): array
    {
        return json_decode($json, true);
    }

    public function testApiReturnsUuid4ByDefault(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/uuid4');

        $this->assertResponseIsSuccessful();
        $data = $this->decodeJson((string) $client->getResponse()->getContent());

        $this->assertSame('uuid4', $data['type']);
        $this->assertSame(1, $data['count']);
        $this->assertCount(1, $data['uuids']);
        $this->assertTrue(Uuid::isValid($data['uuids'][0]));
        $this->assertSame(4, Uuid::fromString($data['uuids'][0])->getVersion());
    }

    public function testApiReturnsUuid1(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/uuid1');

        $this->assertResponseIsSuccessful();
        $data = $this->decodeJson((string) $client->getResponse()->getContent());

        $this->assertSame('uuid1', $data['type']);
        $this->assertTrue(Uuid::isValid($data['uuids'][0]));
        $this->assertSame(1, Uuid::fromString($data['uuids'][0])->getVersion());
    }

    public function testApiReturnsUuid7(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/uuid7');

        $this->assertResponseIsSuccessful();
        $data = $this->decodeJson((string) $client->getResponse()->getContent());

        $this->assertTrue(Uuid::isValid($data['uuids'][0]));
        $this->assertSame(7, Uuid::fromString($data['uuids'][0])->getVersion());
    }

    public function testApiSupportsBulkCount(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/uuid4?count=5');

        $this->assertResponseIsSuccessful();
        $data = $this->decodeJson((string) $client->getResponse()->getContent());

        $this->assertSame(5, $data['count']);
        $this->assertCount(5, $data['uuids']);
        foreach ($data['uuids'] as $uuid) {
            $this->assertTrue(Uuid::isValid($uuid));
        }
    }

    public function testApiCapsBulkCountAtMax(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/uuid4?count=200');

        $this->assertResponseIsSuccessful();
        $data = $this->decodeJson((string) $client->getResponse()->getContent());

        $this->assertSame(20, $data['count']);
        $this->assertCount(20, $data['uuids']);
    }

    public function testApiRejectsInvalidType(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/invalid');

        $this->assertResponseStatusCodeSame(400);
    }
}
