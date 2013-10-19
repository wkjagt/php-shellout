<?php
namespace ConsoleOut\Connection;

use ConsoleOut\Socket\SocketFactory, ConsoleOut\Socket\SocketManager;
use ConsoleOut\Handler\OutputHandler;

/**
 * The inet implementation if the connection object. Configures a SocketManager
 * and starts it up.
 */
class INETConnection extends Connection
{
    protected $address;

    protected $port;

    protected $maxClients = 10;

    protected $clients = array();
 
    /**
     * Constructor
     * 
     * @param string $address The address to listen on
     * @param int $port    The port to listen on
     */
    public function __construct($address, $port)
    {
        parent::__construct();

        $this->address = $address;
        $this->port = $port;
    }

    /**
     * Setup the Socket manager to use inet sockets using the TCP protocol
     * @return void
     */
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

        # todo make this more dependency injection
        $this->clients = new SocketManager(
            $this->masterSocket,
            $this->maxClients,
            $this->commands,
            $this->handler
            );
    }

    /**
     * Start listening on the master socket
     * @return void
     */
    public function listen()
    {
        $this->setup();
        $this->clients->start();
    }
}