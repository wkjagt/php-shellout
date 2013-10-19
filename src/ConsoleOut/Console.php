<?php

use ConsoleOut\Socket\SocketFactory;

/** 
 * Console object used to send debugging information to
 */
class Console
{
    static $socket;

    static $address = '127.0.0.1';

    static $port = 50000;

    /**
     * Set the address of the socket to send to
     * 
     * @param string $address address of the socket to send to
     */
    protected static function setAddress($address)
    {
        static::$address = $address;
    }

    /**
     * Set the port of the socket to send to
     * 
     * @param int $port port of the socket to send to
     */
    protected static function setPort($port)
    {
        static::$port = $port;
    }

    /**
     * Internal helper function to get a writable socket
     * 
     * @return MasterSocket a writable socket
     */
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

    /**
     * The output function that's called with a variable to send through the socket
     * 
     * @param  mixed $value the value to output over the socket
     * @return void
     */
    public static function out($value)
    {
        // move to web plugin
        if(@$_SERVER && $_SERVER['REQUEST_URI']) {
            static::output(sprintf('<info>Request URI: %s</info>', $_SERVER['REQUEST_URI']));
        }

        static::output($value);
    }

    /**
     * Internal helper function to send a formatted string that represents the
     * variable over the socket
     * 
     * @param  mixed $value the value to send over the socket
     * @return void
     */
    protected static function output($value)
    {
        static::getSocket()->write(print_r($value, true))->close();
    }
}