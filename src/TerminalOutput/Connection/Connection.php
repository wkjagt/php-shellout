<?php

namespace TerminalOutput\Connection;

abstract class Connection
{
    protected $commands;

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
        $this->debug("Stopping");
        $this->commands->continue = false;
        $this->debug("Stopped");
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