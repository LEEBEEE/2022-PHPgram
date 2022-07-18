<?php
use ws\RatchetSocket;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require_once 'application/libs/Config.php';
//require_once 'application/libs/Autoload.php';
require __DIR__ . '/vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new RatchetSocket()
        )
    ),
    8090
);

$server->run();