<?php

namespace App\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\Routing\Annotation\Route;
use \Symfony\Component\HttpFoundation\Response;
use \App\Repository\UuidRepository;

class ApiController extends Controller
{
    public function one($type = 'uuid4'): Response
    {
        $uuidRepository = new UuidRepository();

        return new Response($uuidRepository->getUuid($type));
    }
}