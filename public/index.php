<?php

use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../src/App/Kernel.php';

$kernel = new \App\Kernel('prod', false);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);