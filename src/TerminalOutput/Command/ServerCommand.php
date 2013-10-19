<?php

namespace TerminalOutput\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

use TerminalOutput\Connection\INETConnection;
use TerminalOutput\Handler\HandlerInterface;

/**
 * The Symfony Console Command implementation to run Console Out
 * 
 * Also implements TerminalOutput\Handler\HandlerInterface to be used as the output
 * handler for the server.
 */
class ServerCommand extends Command implements HandlerInterface
{

    protected $defaultAddress = '127.0.0.1';
    protected $defaultPort = 50000;
    protected $output;

    protected $openingScreen = '
             __________  ___ _____________            
             \______   \/   |   \______   \           
              |     ___/    ~    \     ___/           
              |    |   \    Y    /    |               
              |____|    \___|_  /|____|               
                              \/                      
    _________                            .__          
    \_   ___ \  ____   ____   __________ |  |   ____  
    /    \  \/ /  _ \ /    \ /  ___/  _ \|  | _/ __ \ 
    \     \___(  <_> )   |  \\___ (  <_> )  |_\  ___/ 
     \______  /\____/|___|  /____  >____/|____/\___  >
            \/            \/     \/                \/ 
                 ________          __                 
                 \_____  \  __ ___/  |_               
                  /   |   \|  |  \   __\              
                 /    |    \  |  /|  |                
                 \_______  /____/ |__|';


    /**
     * Configure the Console Command
     * 
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('terminal-output:listen')
            ->setDescription('Start Terminal Output')
            ->addOption(
                'address',
                null,
                InputOption::VALUE_OPTIONAL,
                sprintf('Address to listen on (default : %s)', $this->defaultAddress)
            )
            ->addOption(
                'port',
                null,
                InputOption::VALUE_OPTIONAL,
                sprintf('Address to listen on (default : %d)', $this->defaultPort)
            )
            ;
    }

    /**
     * Execute the Console Command
     * 
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $address = $input->getOption('address') ?: $this->defaultAddress;
        $port = $input->getOption('port') ?: $this->defaultPort;

        $output->write(sprintf("%s\n\n", $this->openingScreen));
        $output->writeln(sprintf("<info>    Listening on %s:%d</info>\n\n", $address, $port));

        $c = new INETConnection($address, $port);
        $c->setHandler($this);
        $c->listen();
    }

    /**
     * Called when a socket is connected
     * 
     * @return void
     */
    public function onConnect() {}

    /**
     * Called when new content is received over the socker
     * 
     * @param  string $buffer The content that's received over de socket
     * @return void
     */
    public function onReceive($buffer)
    {
        $this->output->write($buffer);
    }

    /**
     * Called when additional information needs to be displayed
     * 
     * @param  string $string Additional information
     * @return void
     */
    public function info($string)
    {
        $this->output->writeln('');
        $this->output->writeln(sprintf('<info>%s</info>', $string));
    }
}
