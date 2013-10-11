<?php
namespace TerminalOutput\Socket;

class MasterSocket extends Socket
{
    protected $options = array();

    public function __construct(array $options)
    {
        if(!$this->socket = socket_create(
            $options['domain'], $options['type'], $options['protocol'])) {
            
            throw new SocketException();
        }
        $this->options = $options;
    }

    public function bind()
    {
        if(!socket_bind(
            $this->socket, $this->options['address'], $this->options['port'])) {
            
            throw new SocketException();
        }
        return $this;
    }

    public function listen()
    {
        if(!socket_listen ($this->socket, $this->options['backlog'])) {
            throw new SocketException();
        }
        return $this;
    }
}