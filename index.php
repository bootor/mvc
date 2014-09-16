<?php
include('classes/route.command.php');
include('classes/route.urlinterpreter.php');
include('classes/route.commanddispatcher.php');
include('classes/phonebook.php');

$urlInterpreter = new Route_UrlInterpreter();
$command = $urlInterpreter->getCommand();
$commandDispatcher = new Route_CommandDispatcher($command);
$commandDispatcher->Dispatch();
echo "123";