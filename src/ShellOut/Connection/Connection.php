<?php

namespace ShellOut\Connection;

use ShellOut\Handler\HandlerInterface;
use RuntimeException;

/**
 * The base connection class. Takes care of starting and stopping the server
 */
abstract class Connection
{
    protected $commands;

    protected $handler;

    /**
     * Start listening
     * 
     * @return void
     */
    abstract public function listen();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commands = (object) array(
            'continue' => true
        );

        if(!function_exists('pcntl_signal')) {
            throw new RuntimeException('PHP not installed with pcntl');
        }

        declare(ticks = 1);

        pcntl_signal(SIGTERM, array($this, 'stop'));
        pcntl_signal(SIGINT, array($this, 'stop'));
        pcntl_signal(SIGHUP, array($this, 'stop'));
        
        pcntl_signal(SIGCHLD, SIG_IGN);
    }

    /**
     * Stop listening
     * 
     * @return void
     */
    public function stop()
    {
        $this->commands->continue = false;
    }

    /**
     * Setter for output handler
     * 
     * @param [type] $handler [description]
     */
    public function setHandler(HandlerInterface $handler)
    {
        $this->handler = $handler;
    }
}