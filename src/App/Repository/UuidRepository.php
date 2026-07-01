<?php

namespace App\Repository;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UuidRepository
{
    private const UUID_TYPES = [
        'uuid1',
        'uuid4',
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

    public function getUuid4(): string
    {
        return Uuid::uuid4()->toString();
    }

    public function getUuid(string $type): string
    {
        return match ($type) {
            'uuid1' => $this->getUuid1(),
            'uuid4' => $this->getUuid4(),
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
