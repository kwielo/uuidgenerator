<?php

namespace App\Controller;

use App\Repository\UuidRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    private const MAX_BULK = 100;

    public function __construct(private readonly UuidRepository $uuidRepository) {}

    public function index(int $bulk = 1, string $type = 'uuid4'): Response
    {
        $bulk = max(1, min($bulk, self::MAX_BULK));

        $uuids = [];
        for ($i = 0; $i < $bulk; $i++) {
            $uuids[] = $this->uuidRepository->getUuid($type);
        }

        return $this->render('index.html.twig', [
            'uuid_types' => $this->uuidRepository->getTypes(),
            'type' => $type,
            'bulk' => $bulk,
            'uuids' => $uuids,
            'nil' => $this->uuidRepository->getNil(),
        ]);
    }
}
