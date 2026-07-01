<?php

namespace App\Tests\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testApiReturnsUuid4ByDefault(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/uuid4');

        $this->assertResponseIsSuccessful();
        $content = $client->getResponse()->getContent();
        $this->assertIsString($content);
        $this->assertTrue(Uuid::isValid($content));
        $this->assertSame(4, Uuid::fromString($content)->getVersion());
    }

    public function testApiReturnsUuid1(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/uuid1');

        $this->assertResponseIsSuccessful();
        $content = $client->getResponse()->getContent();
        $this->assertIsString($content);
        $this->assertTrue(Uuid::isValid($content));
        $this->assertSame(1, Uuid::fromString($content)->getVersion());
    }

    public function testApiRejectsInvalidType(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/invalid');

        $this->assertResponseStatusCodeSame(400);
    }
}
