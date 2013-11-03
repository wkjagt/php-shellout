<?php

namespace ShellOut\Socket;

/**
 *  Client socket implementation of Socket
 */
class ClientSocket extends Socket
{
    /**
     * Constructor
     * 
     * @param The raw socket accepted by the master socket
     */
    public function __construct($socket)
    {
        $this->socket = $socket;
    }
 
    /**
     * Read from the socket
     * 
     * @return string the information read from the socket
     *
     * @throws SocketException when the socket disconnects or the connection is lost
     */
    public function read()
    {
        if (false === ($buf = @socket_read($this->socket, 2048, PHP_NORMAL_READ))) {
            throw new SocketException();
        }
        return $buf;
    }
}