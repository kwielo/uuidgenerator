<?php

namespace App\Controller;

use App\Repository\UuidRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends AbstractController
{
    public function one($type = "uuid4"): Response
    {
        $uuidRepository = new UuidRepository();

        return new Response($uuidRepository->getUuid($type));
    }
}
