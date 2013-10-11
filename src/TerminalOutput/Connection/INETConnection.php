<?php
namespace TerminalOutput\Connection;

use TerminalOutput\Socket\SocketFactory, TerminalOutput\Socket\SocketManager;

class INETConnection extends Connection
{
    protected $address;

    protected $port;

    protected $maxClients = 10;

    protected $clients = array();
     
    public function __construct($address, $port)
    {
        parent::__construct();

        $this->address = $address;
        $this->port = $port;
    }

    protected function setup()
    {
        $socketOptions = array(
            'domain' => AF_INET,
            'type' => SOCK_STREAM,
            'protocol' => SOL_TCP,
            'address' => $this->address,
            'port' => $this->port,
            'backlog' => $this->maxClients
        );

        $this->masterSocket = SocketFactory::create($socketOptions)->bind()->listen();

        $this->clients = new SocketManager($this->masterSocket, $this->maxClients, $this->commands);
    }

    public function listen()
    {
        $this->setup();
        $this->clients->start();
    }
}