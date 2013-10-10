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

    public function read()
    {
        if (false === ($buf = socket_read($this->socket, 2048, PHP_NORMAL_READ))) {
            throw new SocketException();
        }
        return $buf;
    }

}