<?php

namespace App\Tests\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testIndexPageReturnsOk(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    public function testIndexPageContainsUuid(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $input = $crawler->filter('.uuid-input')->eq(1);
        $value = $input->attr('value');
        $this->assertNotNull($value);
        $this->assertTrue(Uuid::isValid($value));
    }

    public function testIndexPageWithBulkParameter(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/5/uuid4');

        $this->assertResponseIsSuccessful();
        // 5 generated UUIDs + 1 nil row = 6 uuid-line elements
        $this->assertCount(6, $crawler->filter('.uuid-line'));
    }

    public function testIndexPageWithUuid1Type(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/1/uuid1');

        $this->assertResponseIsSuccessful();
        $input = $crawler->filter('.uuid-input')->eq(1);
        $value = $input->attr('value');
        $this->assertNotNull($value);
        $this->assertTrue(Uuid::isValid($value));
        $this->assertSame(1, Uuid::fromString($value)->getVersion());
    }

    public function testIndexPageCapsAtMaxBulk(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/200/uuid4');

        $this->assertResponseIsSuccessful();
        // Capped to 20 + 1 nil = 21
        $this->assertCount(21, $crawler->filter('.uuid-line'));
    }

    public function testIndexPageContainsNilUuid(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $nilInput = $crawler->filter('.uuid-input')->eq(0);
        $this->assertSame('00000000-0000-0000-0000-000000000000', $nilInput->attr('value'));
    }
}
