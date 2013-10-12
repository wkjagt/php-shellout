<?php
namespace TerminalOutput\Socket;

class Socket
{
    protected $socket;

    public function getRawSocket()
    {
        return $this->socket;
    }

    public function write($talkback)
    {
        $talkback = trim($talkback) . "\n";
        socket_write($this->socket, $talkback, strlen($talkback));
    }
    
    public function close()
    {
        socket_close($this->socket);
    }

}