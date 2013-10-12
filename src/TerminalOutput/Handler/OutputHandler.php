<?php

namespace TerminalOutput\Handler;

class OutputHandler implements HandlerInterface
{
    public function onConnect()
    {
        return 'welcome';
    }

    public function onReceive($buffer)
    {
        if(trim($buffer)) {
            return "you said $buffer";            
        }
    }
}