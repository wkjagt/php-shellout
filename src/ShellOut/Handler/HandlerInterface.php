<?php

namespace ShellOut\Handler;

/**
 *  Interface for output handlers. An output handler is a handler object that's used
 *  by the socket manager to send information to about the state of the sockets and
 *  the information received over them
 */
interface HandlerInterface
{
    /**
     * Event handler callback when a new socket is connected
     * 
     * @return void
     */
    public function onConnect();

    /**
     * Callback when new information is received over a socket
     * @param  string $buffer the informatoin received over the socket
     * @return void
     */
    public function onReceive($buffer);

    /**
     * Callback when the socket manager has extra information about the connection
     * @param  string $string the extra information about the connection
     * @return void
     */
    public function info($string);
}
