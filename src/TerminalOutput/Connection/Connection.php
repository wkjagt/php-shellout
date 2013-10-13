<?php

namespace TerminalOutput\Connection;

abstract class Connection
{
    protected $commands;

    protected $handler;

    // abstract public function listen();

    public function __construct()
    {
        $this->commands = (object) array(
            'continue' => true
        );

        declare(ticks = 1);

        pcntl_signal(SIGTERM, array($this, 'stop'));
        pcntl_signal(SIGINT, array($this, 'stop'));
        pcntl_signal(SIGHUP, array($this, 'stop'));
        
        pcntl_signal(SIGCHLD, SIG_IGN);
    }

    public function stop()
    {
        $this->commands->continue = false;
    }

    public function setHandler($handler)
    {
        $this->handler = $handler;
    }

    protected function display($i)
    {
        echo "$i\n";
    }

    protected function debug($m)
    {
        echo "$m\n";
    }

}