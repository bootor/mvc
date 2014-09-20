<?php
class Route_UrlInterpreter 
	{

	public $command;

	function Route_UrlInterpreter()
		{
		$requestURI = explode('/', $_SERVER['REQUEST_URI']);
		$scriptName = explode('/',$_SERVER['SCRIPT_NAME']);
		for($i= 0;$i < sizeof($scriptName);$i++)
			{
			if ($requestURI[$i] == $scriptName[$i])
				{
				unset($requestURI[$i]);
				}
			}

		$commandArray = array_values($requestURI);
		$commandName = $commandArray[0];
        $decodedCommandName = urldecode($commandName);
        $parameters = array_slice($commandArray,1);
        $decodedParameters = array();
        foreach ($parameters as $param) {
            array_push($decodedParameters, urldecode($param));
            }
            $this->command = new Route_Command($decodedCommandName,$decodedParameters);
        }

	function getCommand()
		{
		return $this->command;
		}
	}
?>