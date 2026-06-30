<?php

namespace App;

use App\Controller\ApiController;
use App\Controller\IndexController;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->add('api_one', '/api/{type}')
            ->controller([ApiController::class, 'one'])
            ->defaults(['type' => 'uuid4'])
            ->requirements(['type' => '[a-z0-9]+']);

        $routes->add('index', '/{bulk}/{type}')
            ->controller([IndexController::class, 'index'])
            ->defaults(['bulk' => 1, 'type' => 'uuid4'])
            ->requirements(['bulk' => '\d+', 'type' => '[a-z0-9]+']);
    }
}
