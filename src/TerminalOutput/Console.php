<?php

use TerminalOutput\Socket\SocketFactory;

class Console
{
    static $socket;

    static $address = '127.0.0.1';

    static $port = 50000;

    protected static function getSocket()
    {
        if(static::$socket) {
            return $socket;
        }

        $socketOptions = array(
            'domain' => AF_INET,
            'type' => SOCK_STREAM,
            'protocol' => SOL_TCP,
            'address' => static::$address,
            'port' => static::$port,
        );
        static::$socket = SocketFactory::create($socketOptions)->connect();        
        return static::$socket;
    }

    public static function log($value)
    {
        static::getSocket()->write(print_r($value, true))->close();
    }
}