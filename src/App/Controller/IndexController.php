<?php

namespace App\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\Routing\Annotation\Route;
use \App\Repository\UuidRepository;

class IndexController extends Controller
{
    public function index($bulk = 1, $type = 'uuid4')
    {
        $uuidRepository = new UuidRepository();

        $uuids = [];
        for ($i = 0; $i < $bulk; $i++) {
            $uuids[] = $uuidRepository->getUuid($type);
        }

        return $this->render('index.html.twig', [
            'uuid_types' => $uuidRepository->getTypes(),
            'type' => $type,
            'bulk' => $bulk,
            'uuids' => $uuids,
        ]);
    }
}