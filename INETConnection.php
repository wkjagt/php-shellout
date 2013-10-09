<?php
require 'Connection.php';

class INETConnection extends Connection
{
    protected $address;

    protected $port;

    protected $maxClients = 10;

    protected $sock;

    protected $client_socks = array();
     
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
        while ($this->continue()) 
        {
            //prepare array of readable client sockets
            $read = array();
             
            //first socket is the master socket
            $read[0] = $sock;
             
            //now add the existing client sockets
            for ($i = 0; $i < $max_clients; $i++)
            {
                if($client_socks[$i] != null)
                {
                    $read[$i+1] = $client_socks[$i];
                }
            }
             
            //now call select - blocking call
            if(socket_select($read , $write , $except , null) === false)
            {
                $errorcode = socket_last_error();
                $errormsg = socket_strerror($errorcode);
             
                die("Could not listen on socket : [$errorcode] $errormsg \n");
            }
             
            //if ready contains the master socket, then a new connection has come in
            if (in_array($sock, $read)) 
            {
                for ($i = 0; $i < $max_clients; $i++)
                {
                    if ($client_socks[$i] == null) 
                    {
                        $client_socks[$i] = socket_accept($sock);
                         
                        //display information about the client who is connected
                        if(socket_getpeername($client_socks[$i], $address, $port))
                        {
                            echo "Client $address : $port is now connected to us. \n";
                        }
                         
                        //Send Welcome message to client
                        $message = "Welcome to php socket server version 1.0 \n";
                        $message .= "Enter a message and press enter, and i shall reply back \n";
                        socket_write($client_socks[$i] , $message);
                        break;
                    }
                }
            }
         
            //check each client if they send any data
            for ($i = 0; $i < $max_clients; $i++)
            {
                if (in_array($client_socks[$i] , $read))
                {
                    $input = socket_read($client_socks[$i] , 1024);
                     
                    if ($input == null) 
                    {
                        //zero length string meaning disconnected, remove and close the socket
                        unset($client_socks[$i]);
                        socket_close($client_socks[$i]);
                    }
         
                    $n = trim($input);
         
                    $output = "OK ... $input";
                     
                    echo "Sending output to client \n";
                     
                    //send response to client
                    socket_write($client_socks[$i] , $output);
                }
            }
        }

    }

}



$c = new INETConnection('127.0.0.1', 5000);
$c->listen();
