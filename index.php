<?php
include('route.command.php');
include('route.urlinterpreter.php');
include('route.commanddispatcher.php');
include('phonebook.php');
global $mybook;
$mybook = new phonebook();
$urlInterpreter = new Route_UrlInterpreter();
$command = $urlInterpreter->getCommand();
$commandDispatcher = new Route_CommandDispatcher($command);
global $commandResult;

// A utility function to get the url leading up to the current script.
// Used to make the example portable to other locations.
function getScriptUrl()
        {
                $scriptName = explode('/',$_SERVER['SCRIPT_NAME']);
        	unset($scriptName[sizeof($scriptName)-1]);
		$scriptName = array_values($scriptName);
		return 'http://'.$_SERVER['SERVER_NAME'].implode('/',$scriptName).'/';
        }
?>

<html>
<body>
	<p>
    <?php 
	$commandDispatcher->Dispatch();?>
	</p>
</body>
</html>
