<?php
use TerminalOutput\Connection\INETConnection;

include __DIR__.'/vendor/autoload.php';


$c = new INETConnection('127.0.0.1', 5000);
$c->listen();
