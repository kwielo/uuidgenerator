<?php

namespace App\Repository;

use \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use \Ramsey\Uuid\Uuid;

class UuidRepository 
{
    private const UUID_TYPES = [
        'uuid1',
        'uuid4',
    ];

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
        if (!in_array($type, self::UUID_TYPES)) {
            throw new BadRequestHttpException('Invalid parameter "type" for UuidRepository.getUuid');
        }

        switch($type) {
            case 'uuid1':
                return $this->getUuid1();
                break;
            case 'uuid4':
                return $this->getUuid4();
                break;

        }
    }
}