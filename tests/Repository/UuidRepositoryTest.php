<?php

namespace App\Tests\Repository;

use App\Repository\UuidRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UuidRepositoryTest extends TestCase
{
    private UuidRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new UuidRepository();
    }

    public function testGetTypesReturnsExpectedList(): void
    {
        $types = $this->repository->getTypes();

        $this->assertContains('uuid1', $types);
        $this->assertContains('uuid4', $types);
        $this->assertCount(2, $types);
    }

    public function testGetUuid1ReturnsValidUuid(): void
    {
        $uuid = $this->repository->getUuid1();

        $this->assertTrue(Uuid::isValid($uuid));
        $this->assertSame(1, Uuid::fromString($uuid)->getVersion());
    }

    public function testGetUuid4ReturnsValidUuid(): void
    {
        $uuid = $this->repository->getUuid4();

        $this->assertTrue(Uuid::isValid($uuid));
        $this->assertSame(4, Uuid::fromString($uuid)->getVersion());
    }

    public function testGetUuidDelegatesToCorrectMethod(): void
    {
        $uuid1 = $this->repository->getUuid('uuid1');
        $this->assertTrue(Uuid::isValid($uuid1));
        $this->assertSame(1, Uuid::fromString($uuid1)->getVersion());

        $uuid4 = $this->repository->getUuid('uuid4');
        $this->assertTrue(Uuid::isValid($uuid4));
        $this->assertSame(4, Uuid::fromString($uuid4)->getVersion());
    }

    public function testGetUuidThrowsOnInvalidType(): void
    {
        $this->expectException(BadRequestHttpException::class);

        $this->repository->getUuid('invalid');
    }

    public function testGetNilReturnsNilUuid(): void
    {
        $this->assertSame('00000000-0000-0000-0000-000000000000', $this->repository->getNil());
    }

    public function testGetUuid4ReturnsUniqueValues(): void
    {
        $a = $this->repository->getUuid4();
        $b = $this->repository->getUuid4();

        $this->assertNotSame($a, $b);
    }
}
