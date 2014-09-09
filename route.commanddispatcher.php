<?php
include "PhonebookController.php";

class Route_CommandDispatcher
        {
		var $command;
			
       function Route_CommandDispatcher($command)
                {
                $this->command = $command;
                }

        function Dispatch()
                {
				$controller = new PhonebookController;
				switch ($this->command->getCommandName())
                        {
                        case 'delete' : 
                                $controller->delete($this->command);
                                break;
                        case 'add' : 
                                $controller->add($this->command);
                                break;
                        case 'search' : 
                                $controller->search($this->command);
                                break;
						case 'sort' : 
                                $controller->tosort($this->command);
                                break;
						case 'root': 
                                $controller->index($this->command);
                                break;
                        default: 
                                $controller->index($this->command);
                                break;
                        }
                }
        }
?>