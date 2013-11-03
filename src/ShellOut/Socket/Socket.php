<?php
namespace ShellOut\Socket;

/** 
 * Base Socket class for MasterSocket and ClientSocket
 */
class Socket
{
    protected $socket;

    /**
     * Get the raw socket resource
     * 
     * @return resource The raw socket
     */
    public function getRawSocket()
    {
        return $this->socket;
    }

    /**
     * Write to the socket
     * 
     * @param  string $talkback The string to write to the socket
     * @return Socket the current object, for chaning
     */
    public function write($talkback)
    {
        $talkback = trim($talkback) . "\n";

        @socket_write($this->socket, $talkback, strlen($talkback));
        return $this;
    }

    /**
     * Close the socket
     * 
     * @return void
     */
    public function close()
    {
        socket_close($this->socket);
    }

}