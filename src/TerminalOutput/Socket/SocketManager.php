<?php

namespace TerminalOutput\Socket;

use TerminalOutput\Handler\HandlerInterface;


class SocketManager
{
    protected $masterSocket;

    protected $read = array();

    protected $clients = array();

    protected $size;

    protected $commands;

    protected $handler;

    public function __construct(MasterSocket $masterSocket, $size, $commands, HandlerInterface $handler)
    {
        $this->masterSocket = $masterSocket;
        $this->size = $size;
        $this->commands = $commands;
        $this->handler = $handler;
    }

    public function start()
    {
        do {
            $this->clients = array_values($this->clients);
            $this->read = array_merge(array($this->masterSocket), $this->clients);

            if($this->select() < 1) {
                continue;                
            }
            
            $this->accept()->read();
        } while ($this->commands->continue);

        $this->handler->info('Closing...');
    }

    protected function select()
    {
        $read = array();

        foreach($this->read as $socket) {
            $read[] = $socket->getRawSocket();
        }
        $selected = @socket_select($read, $write = NULL, $except = NULL, $tv_sec = 5);

        // remove from $this->read if not $read
        foreach($this->read as $k => $r) {
            if(false === in_array($r->getRawSocket(), $read)) {
                unset($this->read[$k]);
            }
        }
        return $selected;
    }

    protected function accept()
    {
        if (in_array($this->masterSocket, $this->read)) {
            $newClient = SocketFactory::create($this->masterSocket);

            if($welcome = $this->handler->onConnect()) {
                $newClient->write($welcome);
            }
            $this->clients[] = $newClient;
        }
        return $this;
    }

    protected function read()
    {
        foreach ($this->clients as $key => $client) {
            if (in_array($client, $this->read)) {
                try {
                    $buf = $client->read();

                    if($response = $this->handler->onReceive($buf)) {
                        $client->write($response);
                    }
                } catch(\Exception $e) {
                    unset($this->clients[$key]);
                    $client->close();                    
                }
            }            
        }        
    }

    public function __destruct()
    {
        $this->masterSocket->close();
    }
}