<?php
class Route_Command
        {
        var $commandName = '';
        var $parameters = array();
        
        function Route_Command($commandName,$parameters)
                {
                $this->commandName = $commandName;
                $this->parameters = $parameters;
                }
        function getCommandName()
                {
                return $this->commandName;
                }
        function getParameters()
                {
                return $this->parameters;
                }
        }
?>