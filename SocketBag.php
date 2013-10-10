<?php

class SocketBag
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

    protected function setRead()
    {
        $this->read = array();
        $this->read[] = $this->masterSocket;
        
        $this->read = array_merge($this->read, $this->clients);
        return $this;
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
        return $selected < 1;
    }

    protected function accept()
    {
        if (in_array($this->masterSocket, $this->read)) {
            $this->clients[] = SocketFactory::create($this->masterSocket);
        }
    }

    protected function read()
    {
        foreach ($this->clients as $client) {
            if (in_array($client, $this->read)) {
                if (false === ($buf = socket_read($client->getRawSocket(), 2048, PHP_NORMAL_READ))) {
                    throw new SocketException();
                }
                echo $buf;
            }            
        }        
    }

    public function start()
    {
        do {
            $this->setRead();
            if($this->select()) continue;
            $this->accept();
            $this->read();
        } while (true);
    }

    public function __destruct()
    {
        $this->masterSocket->close();
    }
}