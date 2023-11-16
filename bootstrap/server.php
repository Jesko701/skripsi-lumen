<?php

use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\HeaderBag;
use React\EventLoop\Loop;
use React\Http\Message\Response;

$app = require __DIR__.'/../bootstrap/app.php';

$loop = Loop::get();
$ip = env('IP');
$port = env('PORT');
$handler = function (ServerRequestInterface $request) use ($app) {
    $parsedBody = $request->getParsedBody();
    if (empty($parsedBody) || !is_array($parsedBody)) {
        $parsedBody = [];
    }

    $lumenRequest = Request::create(
        $request->getUri(),
        $request->getMethod(),
        array_merge($request->getQueryParams(), $parsedBody),
        $request->getCookieParams(),
        $request->getUploadedFiles(),
        $request->getServerParams(),
        $request->getBody()->getContents()
    );

    $lumenRequest->headers = new HeaderBag($request->getHeaders());
    $response = $app->dispatch($lumenRequest);
    return new Response(
        $response->getStatusCode(),
        $response->headers->all(),
        $response->getContent()
    );
};

$server = new React\Http\HttpServer($handler);

$socket = new React\Socket\SocketServer("{$ip}:{$port}");
$server->listen($socket);
echo "Server running at http://$ip:$port\n";
$loop->run();