<?php

namespace App;

use \Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use \Symfony\Component\Config\Loader\LoaderInterface;
use \Symfony\Component\DependencyInjection\ContainerBuilder;
use \Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpKernel\Kernel as BaseKernel;
use \Symfony\Component\Routing\RouteCollectionBuilder;
use \Symfony\Component\Routing\Route;

require __DIR__.'/../../vendor/autoload.php';

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles(): array
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
        ];
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader): void
    {
        $loader->load(__DIR__.'/../../config/config.yaml');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $routes->add('/api', '\App\Controller\ApiController:one');
        $routes->addRoute(new Route(
            '/api/{type}',
            [
                'type' => 'uuid4',
                '_controller' => '\App\Controller\ApiController:one',
            ]
        ));
        $routes->add('/', '\App\Controller\IndexController:index');
        $routes->addRoute(new Route(
            '/{bulk}',
            [
                'bulk' => '1',
                '_controller' => '\App\Controller\IndexController:index',
            ]
        ));
        $routes->addRoute(new Route(
            '/{bulk}/{type}',
            [
                'bulk' => '1',
                'type' => 'uuid4',
                '_controller' => '\App\Controller\IndexController:index',
            ]
        ));
    }

    // optional, to use the standard Symfony cache directory
    public function getCacheDir(): string
    {
        return __DIR__.'/../var/cache/'.$this->getEnvironment();
    }

    // optional, to use the standard Symfony logs directory
    public function getLogDir(): string
    {
        return __DIR__.'/../var/log';
    }
}