<?php

namespace ConsoleOut\Socket;

use RuntimeException;

/**
 * SocketException class. Thrown when errors in socket objects occur
 * Uses the socket error function to construct the message and the code for
 * the RuntimeException that it extends
 */
class SocketException extends RuntimeException
{
    /**
     * Constructor
     * 
     * @param string  $message
     * @param integer $code
     * @param Exception  $previous
     */
    public function __construct($message = '', $code = 0, $previous = NULL)
    {
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);
        
        parent::__construct($errormsg, $errorcode);
    }
}