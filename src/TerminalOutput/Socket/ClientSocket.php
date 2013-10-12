<?php
namespace TerminalOutput\Socket;

class ClientSocket extends Socket
{
    public function __construct($socket)
    {
        $this->socket = $socket;
    }
 
    public function read()
    {
        if (false === ($buf = socket_read($this->socket, 2048, PHP_NORMAL_READ))) {
            throw new SocketException();
        }
        return $buf;
    }
}