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

        $this->assertSame(['uuid1', 'uuid3', 'uuid4', 'uuid5', 'uuid6', 'uuid7'], $types);
        $this->assertCount(6, $types);
    }

    public function testGetUuid1ReturnsValidUuid(): void
    {
        $uuid = $this->repository->getUuid1();

        $this->assertTrue(Uuid::isValid($uuid));
        $this->assertSame(1, Uuid::fromString($uuid)->getVersion());
    }

    public function testGetUuid3ReturnsValidUuid(): void
    {
        $uuid = $this->repository->getUuid3();

        $this->assertTrue(Uuid::isValid($uuid));
        $this->assertSame(3, Uuid::fromString($uuid)->getVersion());
    }

    public function testGetUuid4ReturnsValidUuid(): void
    {
        $uuid = $this->repository->getUuid4();

        $this->assertTrue(Uuid::isValid($uuid));
        $this->assertSame(4, Uuid::fromString($uuid)->getVersion());
    }

    public function testGetUuid5ReturnsValidUuid(): void
    {
        $uuid = $this->repository->getUuid5();

        $this->assertTrue(Uuid::isValid($uuid));
        $this->assertSame(5, Uuid::fromString($uuid)->getVersion());
    }

    public function testGetUuid6ReturnsValidUuid(): void
    {
        $uuid = $this->repository->getUuid6();

        $this->assertTrue(Uuid::isValid($uuid));
        $this->assertSame(6, Uuid::fromString($uuid)->getVersion());
    }

    public function testGetUuid7ReturnsValidUuid(): void
    {
        $uuid = $this->repository->getUuid7();

        $this->assertTrue(Uuid::isValid($uuid));
        $this->assertSame(7, Uuid::fromString($uuid)->getVersion());
    }

    public function testGetUuidDelegatesToCorrectMethod(): void
    {
        $uuid1 = $this->repository->getUuid('uuid1');
        $this->assertTrue(Uuid::isValid($uuid1));
        $this->assertSame(1, Uuid::fromString($uuid1)->getVersion());

        $uuid4 = $this->repository->getUuid('uuid4');
        $this->assertTrue(Uuid::isValid($uuid4));
        $this->assertSame(4, Uuid::fromString($uuid4)->getVersion());

        $uuid7 = $this->repository->getUuid('uuid7');
        $this->assertTrue(Uuid::isValid($uuid7));
        $this->assertSame(7, Uuid::fromString($uuid7)->getVersion());
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
