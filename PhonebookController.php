<?php
class PhonebookController {
	
	public function add($command) {
	/**
	 * Create new entity in db
	 * 
	 * @param Route_Command $command
	 * 
	 */
		global $commandResult, $mybook;
		$this->command = $command;
		$emptyfields = false;
		if (sizeof($this->command->getParameters()) >= 2) {
			$parameters = $this->command->getParameters();
			$element['name'] = $parameters[0];
			$element['phone'] = $parameters[1];
			$mybook->add_to_db($element);
			$url = dirname($_SERVER['SCRIPT_NAME']);
			Header("Location: $url");
			exit();
		} else {
			if (isset($_REQUEST['doAdd'])) {
				$element = $_REQUEST['element'];
				if (ini_get("magic_quotes_gpc"))
					$element = array_map('stripslashes', $element);
				if (($element['name'] == "") OR ($element['phone'] == "")) {
					$emptyfields = true;
				} else {
					$url = dirname($_SERVER['SCRIPT_NAME']); 
					$mybook->add_to_db($element);
					Header("Location: $url");
					exit();
				}
			}
		}
		include "add.html";
	}

	public function search($command) {
	/**
	 * Search records in db
	 * 
	 * @param Route_Command $command
	 * 
	 */	
		global $commandResult, $mybook;
		$this->command = $command;
		$flag = 0;
		$searchbook = array();
		$sort = "name";
		$direction = 'up';
		$emptyfields = false;
		$element['name'] = '';
		$element['phone'] = '';
		if (sizeof($this->command->getParameters()) > 1) {
			$parameters = $this->command->getParameters();
			$parameters[0] == 'phone' ? $sort = 'phone' : $sort = 'name';
			$parameters[1] == 'down' ? $direction = 'down' : $direction = 'up';
		}
		if (sizeof($this->command->getParameters()) > 2) {
			$element['name'] = $parameters[2];
			if (sizeof($this->command->getParameters()) > 3)
				$element['phone'] = $parameters[3];
			if ($element['name'] != '' OR $element['phone'] != '') {
				$flag = 1;
				$mybook->get_search_data($element);
			}
		}
		$searchbook = $mybook->get_searched_data($sort, $direction);
		
		if (count($searchbook) === 0 AND $flag === 1) echo "No match founded...<br><hr>";
		
		if (isset($_REQUEST['doSearch'])) {
			$element = $_REQUEST['element'];
			if (ini_get("magic_quotes_gpc"))
				$element = array_map('stripslashes', $element);
			if (($element['name'] == "") AND ($element['phone'] == "")) {
				$emptyfields = true; // echo "<hr>Empty fields. Search is impossible.<hr>";
			} else {
				$url = dirname($_SERVER['SCRIPT_NAME']); 
				$url .= '/search/'.$element['name'].'/'.$element['phone'];
				Header("Location: $url");
				exit();
			}
		}
		include "search.html";
	}

	public function delete($command) {
	/**
	 * Delete records from db
	 * 
	 * @param Route_Command $command
	 * 
	 */	
		global $commandResult, $mybook;
		$this->command = $command;
		if (sizeof($this->command->getParameters()) > 0)
		{
			$parameters = $this->command->getParameters();
			$mybook->del_from_db($parameters[0]);
			$url = dirname($_SERVER['SCRIPT_NAME']);
			Header("Location: $url");
			exit();
		}
	}

	public function index($command) {
	/**
	 * Show all records from db
	 * 
	 * @param Route_Command $command
	 * 
	 */	
		global $commandResult, $mybook;
		$this->command = $command;
		$sort = 'name';
		if (sizeof($this->command->getParameters()) > 0) {
			$parameters = $this->command->getParameters();
			if ($parameters[0] == "1") $sort = "phone";
		}
		$book = $mybook->get_sorted_data($sort, 'up');
		include "main.html";
	}

	public function tosort($command) {
	/**
	 * Sort records from db on main page
	 * 
	 * @param Route_Command $command
	 * 
	 */	
		global $commandResult, $mybook;
		$this->command = $command;
		$sort = 'name';
		$direction = 'up';
		if (sizeof($this->command->getParameters()) > 1) {
			$parameters = $this->command->getParameters();
			if ($parameters[0] == "phone") $sort = "phone";
			if ($parameters[1] == "down") $direction = "down";
		}
		$book = $mybook->get_sorted_data($sort, $direction);
		include "main.html";
	}
}
?>
