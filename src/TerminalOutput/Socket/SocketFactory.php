<?php

namespace TerminalOutput\Socket;

use RuntimeException;

/** 
 * Socket factory class to create sockets
 */
class SocketFactory
{
    /** 
     * Create a new socket
     * @param  mixed $arg if an array of options is passed, a new MasterSocket
     *                    is instantiated. The other possibility is that 
     * @return Socket
     */
    public static function create($arg)
    {
        if(is_array($arg)) {
            return new MasterSocket($arg);
        } elseif($arg instanceof MasterSocket) {

            if(!$clientSocket = $arg->accept()) {
                throw new SocketException();                
            }
            return new ClientSocket($clientSocket);
        }

        throw new RuntimeException('Argument must be either an array of options,
            or an instance of MasterSocket');
    }
}