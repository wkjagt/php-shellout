<?php

use TerminalOutput\Socket\SocketFactory;

include __DIR__.'/vendor/autoload.php';


$socketOptions = array(
    'domain' => AF_INET,
    'type' => SOCK_STREAM,
    'protocol' => SOL_TCP,
    'address' => '127.0.0.1',
    'port' => '5000',
);

$socket = SocketFactory::create($socketOptions)->connect();


$test = var_export(array(1, 2, 3), true);


$socket->write($test);

sleep(3);