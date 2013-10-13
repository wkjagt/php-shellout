<?php

use TerminalOutput\Socket\SocketFactory;

class Console
{
    static $socket;

    static $address = '127.0.0.1';

    static $port = 50000;

    protected static function setAddress($address)
    {
        static::$address = $address;
    }

    protected static function setPort($port)
    {
        static::$port = $port;
    }

    protected static function getSocket()
    {
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
        // move to web plugin
        if(@$_SERVER && $_SERVER['REQUEST_URI']) {
            static::output(sprintf('<info>Request URI: %s</info>', $_SERVER['REQUEST_URI']));
        }

        static::output($value);
    }

    public static function output($value)
    {
        static::getSocket()->write(print_r($value, true))->close();
    }

}