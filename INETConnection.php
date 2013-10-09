<?php
require 'Connection.php';

class INETConnection extends Connection
{
    protected $address;

    protected $port;

    protected $maxClients = 10;

    protected $sock;

    protected $clientSocks = array();
     
    protected $read = array();

    public function __construct($address, $port)
    {
        parent::__construct();

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

        while ($this->cont()) 
        {
            $this->setReadSockets();
            $this->waitForSocket();
            $this->accept();
            $this->receive();
        }

    }

    protected function waitForSocket()
    {
        if(socket_select($this->read , $write , $except , null) === false){
            $this->socketError('Could not listen on socket');
        }
    }

    protected function setReadSockets()
    {
        $this->read = array();

        //first socket is the master socket
        $this->read[0] = $this->sock;
         
        //now add the existing client sockets
        for ($i = 0; $i < $this->maxClients; $i++) {
            if(@$this->clientSocks[$i] != null) {
                $this->read[$i+1] = $this->clientSocks[$i];
            }
        }
    }

    protected function accept()
    {
        if (in_array($this->sock, $this->read)) {
            for ($i = 0; $i < $this->maxClients; $i++) {
                if (@$this->clientSocks[$i] == null)  {
                    $this->clientSocks[$i] = socket_accept($this->sock);
                     
                    //display information about the client who is connected
                    if(socket_getpeername($this->clientSocks[$i], $address, $port)) {
                        $this->debug("Client $address : $port is now connected to us.");
                    }

                    //Send Welcome message to client
                    $message = "Welcome to php socket server version 1.0 \n";
                    $message .= "Enter a message and press enter, and i shall reply back \n";
                    socket_write($this->clientSocks[$i] , $message);
                    break;

                }
            }
        }

    }

    protected function receive()
    {
        //check each client if they sent any data
        for ($i = 0; $i < $this->maxClients; $i++) {
            if (in_array(@$this->clientSocks[$i] , $this->read)) {
                $input = socket_read($this->clientSocks[$i] , 1024);
                 
                if ($input == null) {
                    //zero length string meaning disconnected, remove and close the socket
                    socket_close($this->clientSocks[$i]);
                    unset($this->clientSocks[$i]);
                }
                $n = trim($input);
                $this->debug($n);
            }
        }

    }

}



$c = new INETConnection('127.0.0.1', 5000);
$c->listen();
