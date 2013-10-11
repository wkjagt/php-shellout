<?php

namespace TerminalOutput\Socket;

class SocketManager
{
    protected $masterSocket;

    protected $read = array();

    protected $clients = array();

    protected $size;

    public function __construct(MasterSocket $masterSocket, $size)
    {
        $this->masterSocket = $masterSocket;
        $this->size = $size;
    }

    public function start()
    {
        do {
            $this->read = array_merge(array($this->masterSocket), $this->clients);

            if($this->select() < 1) {
                continue;                
            }
            
            $this->accept()->read();
        } while (true);
    }

    protected function select()
    {
        $read = array();

        foreach($this->read as $socket) {
            $read[] = $socket->getRawSocket();
        }
        $selected = socket_select($read, $write = NULL, $except = NULL, $tv_sec = 5);

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
            $this->clients[] = SocketFactory::create($this->masterSocket);
        }
        return $this;
    }

    protected function read()
    {
        foreach ($this->clients as $client) {
            if (in_array($client, $this->read)) {
                $buf = $client->read();
                echo $buf;
            }            
        }        
    }

    public function __destruct()
    {
        $this->masterSocket->close();
    }
}