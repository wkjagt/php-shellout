<?php

namespace TerminalOutput\Socket;

class SocketFactory
{
    public static function create($arg)
    {
        if(is_array($arg)) {
            return new MasterSocket($arg);
        } else {

            if (($msgsock = socket_accept($arg->getRawSocket())) === false) {
                throw new SocketException();
            }

            return new ClientSocket($msgsock);
        }


    }
}