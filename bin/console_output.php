<?php

include __DIR__.'/../vendor/autoload.php';

$console = new Symfony\Component\Console\Application();
$console->add(new TerminalOutput\Command\ServerCommand);
$console->run();