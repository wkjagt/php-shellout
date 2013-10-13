<?php

namespace TerminalOutput\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

use TerminalOutput\Connection\INETConnection;
use TerminalOutput\Handler\HandlerInterface;

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

    public function onConnect()
    {
    }

    public function onReceive($buffer)
    {
        $this->output->write($buffer);
    }

    public function info($string)
    {
        $this->output->writeln('');
        $this->output->writeln(sprintf('<info>%s</info>', $string));
    }
}
