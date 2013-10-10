<?php

class Socket
{
    protected $socket;

    public function getRawSocket()
    {
        return $this->socket;
    }

    public function close()
    {
        socket_close($this->socket);
    }

}