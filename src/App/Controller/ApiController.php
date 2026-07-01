<?php

namespace App\Controller;

use App\Repository\UuidRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends AbstractController
{
    public function __construct(private readonly UuidRepository $uuidRepository) {}

    public function one(Request $request, string $type = 'uuid4'): JsonResponse
    {
        $count = max(1, min((int) $request->query->get('count', 1), UuidRepository::MAX_BULK));

        $uuids = [];
        for ($i = 0; $i < $count; $i++) {
            $uuids[] = $this->uuidRepository->getUuid($type);
        }

        return new JsonResponse([
            'type' => $type,
            'count' => $count,
            'uuids' => $uuids,
        ]);
    }
}
