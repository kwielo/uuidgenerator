<?php

namespace App\Controller;

use App\Repository\UuidRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    public function index($bulk = 1, $type = "uuid4"): Response
    {
        $uuidRepository = new UuidRepository();

        $uuids = [];
        for ($i = 0; $i < $bulk; $i++) {
            $uuids[] = $uuidRepository->getUuid($type);
        }

        return $this->render("index.html.twig", [
            "uuid_types" => $uuidRepository->getTypes(),
            "type" => $type,
            "bulk" => $bulk,
            "uuids" => $uuids,
            "nil" => $uuidRepository->getNil(),
        ]);
    }
}
