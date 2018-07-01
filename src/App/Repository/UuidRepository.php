<?php

namespace App\Repository;

use \Ramsey\Uuid\Uuid;

class UuidRepository 
{
    private const UUID_TYPES = [
        'uuid1',
        'uuid4',
    ];

    public function getTypes() 
    {
        return self::UUID_TYPES;
    }

    public function getUuid1()
    {
        return Uuid::uuid1()->toString();
    }

    public function getUuid4()
    {
        return Uuid::uuid4()->toString();
    }
}