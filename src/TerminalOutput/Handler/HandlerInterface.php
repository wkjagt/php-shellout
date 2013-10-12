<?php

namespace TerminalOutput\Handler;

interface HandlerInterface
{
    public function onConnect();

    public function onReceive($buffer);
}
