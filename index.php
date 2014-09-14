<?php
include('route.command.php');
include('route.urlinterpreter.php');
include('route.commanddispatcher.php');

$urlInterpreter = new Route_UrlInterpreter();
$command = $urlInterpreter->getCommand();
$commandDispatcher = new Route_CommandDispatcher($command);

include "dispatch.html";
}
?>