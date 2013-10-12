<?php

namespace TerminalOutput\Socket;

use RuntimeException;

class SocketException extends RuntimeException
{
    public function __construct($message = '', $code = 0, $previous = NULL)
    {
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);
        
        parent::__construct($errormsg, $errorcode);
    }
}