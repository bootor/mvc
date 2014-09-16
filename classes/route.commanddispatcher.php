<?php
include (__DIR__ . '/../controllers/PhonebookController.php');

class Route_CommandDispatcher {
	public $command;

	function Route_CommandDispatcher($command) {
		$this->command = $command;
	}

	function Dispatch() {
		$controller = new PhonebookController;
		$action = $this->command->getCommandName();
		if (method_exists($controller, $action))
			$controller->$action($this->command);
		else 
			$controller->index($this->command);
	}
}
?>
