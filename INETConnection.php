<?php


class INETConnection
{
    protected $address;

    protected $port;

    protected $maxClients = 10;

    protected $sock;

    protected $client_socks = array();
     
    protected $read = array();

    public function __construct($address, $port)
    {
        $this->address = $address;
        $this->port = $port;

    }

    protected function createSocket()
    {
        if(!$this->sock = socket_create(AF_INET, SOCK_STREAM, 0)) {
            $this->socketError("Couldn't create socket");
        }
        $this->debug("Socket created");

        if(!socket_bind($this->sock, $this->address, $this->port)) {
            $this->socketError("Could not bind socket");
        }
        $this->debug("Socket bind OK");

        if(!socket_listen ($this->sock , $this->maxClients)) {
            $this->socketError("Could not listen on socket");
        }
        $this->debug("Socket listen OK");
    }

    public function listen()
    {
        $this->createSocket();

        while (true) {
            $client = socket_accept($this->sock);
            $input = socket_read($client, 1024000);
            
            $this->display($input);
        }
    }

    protected function display($i)
    {
        echo "$i\n";
    }

    protected function debug($m)
    {
        echo "$m\n";
    }

    protected function socketError($m)
    {
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);

        throw new RunTimeException("$m : [$errorcode] $errormsg");
    }
}



$c = new INETConnection('127.0.0.1', 5000);
$c->listen();
