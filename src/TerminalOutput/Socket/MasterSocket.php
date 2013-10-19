<?php
namespace TerminalOutput\Socket;

/**
 *  Master socket implementation of Socket. This is the socket that listens
 *  for incoming connections
 */
class MasterSocket extends Socket
{
    protected $options = array();

    /**
     * Constructor.
     * 
     * @param array $options an array of options. Having the following keys
     *       'domain' => the protocol family to be used by the socket
     *       'type' => the type of communication to be used by the socket
     *       'protocol' => protocol within the specified domain to be used when communicating
     *       'address' => addres (if domain is inet) or path (if domain is unix)
     *       'port' => port to listen on (if inet)
     *       'backlog' => maximum of backlog incoming connections taht will be queued for processing
     *
     */
    public function __construct(array $options)
    {
        if(!$this->socket = socket_create(
            $options['domain'], $options['type'], $options['protocol'])) {
            
            throw new SocketException();
        }
        $this->options = $options;
    }

    /**
     * Bind the socket to the address specified in the options
     * 
     * @return Socket the current object, for chaning
     */
    public function bind()
    {
        if(!socket_bind(
            $this->socket, $this->options['address'], $this->options['port'])) {
            
            throw new SocketException();
        }
        return $this;
    }

    /**
     * Connect the socket to the address specified in the options
     * 
     * @return Socket the current object, for chaning
     */
    public function connect()
    {
        if(!socket_connect(
            $this->socket, $this->options['address'], $this->options['port'])) {
            
            throw new SocketException();
        }
        return $this;
    }

    /**
     * Start listening
     * 
     * @return Socket the current object, for chaning
     */
    public function listen()
    {
        if(!socket_listen ($this->socket, $this->options['backlog'])) {
            throw new SocketException();
        }
        return $this;
    }

    public function accept()
    {
        if (($msgsock = socket_accept($this->socket)) === false) {
            throw new SocketException();
        }

        return $msgsock;
    }
}