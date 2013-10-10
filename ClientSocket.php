<?php

class ClientSocket
{
    protected $socket;

    public function __construct($socket)
    {
        $this->socket = $socket;
    }
    
    public function getRawSocket()
    {
        return $this->socket;
    }
}