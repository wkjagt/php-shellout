<?php

abstract class Connection
{
    protected $continue = true;

    // abstract public function listen();

    public function __construct()
    {
        declare(ticks = 1);

        pcntl_signal(SIGTERM, array($this, 'stop'));
        pcntl_signal(SIGINT, array($this, 'stop'));
    }

    public function stop()
    {
        $this->debug("Stopping");
        $this->continue = false;
        $this->debug("Stopped");
    }

    protected function cont()
    {
        return $this->continue;
    }

    protected function display($i)
    {
        echo "$i\n";
    }

    protected function debug($m)
    {
        echo "$m\n";
    }


    protected function socketError($m)
    {
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);

        throw new RunTimeException("$m : [$errorcode] $errormsg");
    }
}