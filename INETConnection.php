<?php
require 'Connection.php';
require 'Socket.php';
require 'MasterSocket.php';
require 'ClientSocket.php';
require 'SocketBag.php';
require 'SocketException.php';
require 'SocketFactory.php';

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

        $this->clients = new SocketBag($this->masterSocket, $this->maxClients);
    }

    public function listen()
    {
        $this->setup();
        $this->clients->start();



        // $sock = $this->masterSocket->getRawSocket();

        // //clients array
        // $clients = array();

        // do {
        //     $read = array();
        //     $read[] = $sock;
            
        //     $read = array_merge($read,$clients);
            
        //     // Set up a blocking call to socket_select
        //     if(socket_select($read, $write = NULL, $except = NULL, $tv_sec = 5) < 1)
        //     {
        //         //    SocketServer::debug("Problem blocking socket_select?");
        //         continue;
        //     }
            
        //     // Handle new Connections
        //     if (in_array($sock, $read)) {        
                
        //         if (($msgsock = socket_accept($sock)) === false) {
        //             echo "socket_accept() falló: razón: " . socket_strerror(socket_last_error($sock)) . "\n";
        //             break;
        //         }
        //         $clients[] = $msgsock;
        //         $key = array_keys($clients, $msgsock);
        //         /* Enviar instrucciones. */
        //         $msg = "\nBienvenido al Servidor De Prueba de PHP. \n" .
        //         "Usted es el cliente numero: {$key[0]}\n" .
        //         "Para salir, escriba 'quit'. Para cerrar el servidor escriba 'shutdown'.\n";
        //         socket_write($msgsock, $msg, strlen($msg));
                
        //     }
            
        //     // Handle Input
        //     foreach ($clients as $key => $client) { // for each client        
        //         if (in_array($client, $read)) {
        //             if (false === ($buf = socket_read($client, 2048, PHP_NORMAL_READ))) {
        //                 echo "socket_read() falló: razón: " . socket_strerror(socket_last_error($client)) . "\n";
        //                 break 2;
        //             }
        //             if (!$buf = trim($buf)) {
        //                 continue;
        //             }
        //             if ($buf == 'quit') {
        //                 unset($clients[$key]);
        //                 socket_close($client);
        //                 break;
        //             }
        //             if ($buf == 'shutdown') {
        //                 socket_close($client);
        //                 break 2;
        //             }
        //             $talkback = "Cliente {$key}: Usted dijo '$buf'.\n";
        //             socket_write($client, $talkback, strlen($talkback));
        //             echo "$buf\n";
        //         }
                
        //     }        
        // } while (true);

        // socket_close($sock);
    }
}



$c = new INETConnection('127.0.0.1', 5000);
$c->listen();
