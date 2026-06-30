<?php

namespace App\Controller;

use App\Repository\UuidRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends AbstractController
{
    public function __construct(private readonly UuidRepository $uuidRepository)
    {
    }

    public function one(string $type = 'uuid4'): Response
    {
        return new Response($this->uuidRepository->getUuid($type));
    }
}
