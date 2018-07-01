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
            switch($type) {
                case 'uuid1':
                    $uuid = $uuidRepository->getUuid1();
                    break;
                case 'uuid4':
                default:
                    $uuid = $uuidRepository->getUuid4();
                    break;

            }
            $uuids[] = $uuid;
        }

        return $this->render('index.html.twig', [
            'uuid_types' => $uuidRepository->getTypes(),
            'type' => $type,
            'bulk' => $bulk,
            'uuids' => $uuids,
        ]);
    }
}