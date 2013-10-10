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
        $this->read[] = $this->masterSocket->getRawSocket();
        
        $this->read = array_merge($this->read, $this->clients);
        return $this;
    }

    protected function select()
    {
        if(socket_select($this->read, $write = NULL, $except = NULL, $tv_sec = 5) < 1) {
            return true;
        }        
        return false;
    }

    protected function accept()
    {
        if (in_array($this->masterSocket->getRawSocket(), $this->read)) {        
            $this->clients[] = SocketFactory::create($this->masterSocket)->getRawSocket();
        }
    }

    protected function read()
    {
        foreach ($this->clients as $key => $client) { // for each client        
            if (in_array($client, $this->read)) {
                if (false === ($buf = socket_read($client, 2048, PHP_NORMAL_READ))) {
                    throw new SocketException();
                }
                echo "$buf\n";
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
        socket_close($this->masterSocket->close());        
    }
}