<?php

namespace App\Repository;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UuidRepository
{
    public const MAX_BULK = 100;

    private const UUID_TYPES = [
        'uuid1',
        'uuid3',
        'uuid4',
        'uuid5',
        'uuid6',
        'uuid7',
    ];

    /**
     * @return string[]
     */
    public function getTypes(): array
    {
        return self::UUID_TYPES;
    }

    public function getUuid1(): string
    {
        return Uuid::uuid1()->toString();
    }

    public function getUuid3(): string
    {
        return Uuid::uuid3(Uuid::NAMESPACE_DNS, 'uuidgenerator.wielo.co')->toString();
    }

    public function getUuid4(): string
    {
        return Uuid::uuid4()->toString();
    }

    public function getUuid5(): string
    {
        return Uuid::uuid5(Uuid::NAMESPACE_DNS, 'uuidgenerator.wielo.co')->toString();
    }

    public function getUuid6(): string
    {
        return Uuid::uuid6()->toString();
    }

    public function getUuid7(): string
    {
        return Uuid::uuid7()->toString();
    }

    public function getUuid(string $type): string
    {
        return match ($type) {
            'uuid1' => $this->getUuid1(),
            'uuid3' => $this->getUuid3(),
            'uuid4' => $this->getUuid4(),
            'uuid5' => $this->getUuid5(),
            'uuid6' => $this->getUuid6(),
            'uuid7' => $this->getUuid7(),
            default => throw new BadRequestHttpException(
                sprintf('Invalid parameter "type" "%s" for UuidRepository::getUuid', $type),
            ),
        };
    }

    public function getNil(): string
    {
        return Uuid::NIL;
    }
}
