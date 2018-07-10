<?php

use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../src/App/Kernel.php';

if (false !== strpos($_SERVER['HTTP_HOST'], 'localhost')) {
    $kernel = new \App\Kernel('dev', true);
} else {
    $kernel = new \App\Kernel('prod', false);
}
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);