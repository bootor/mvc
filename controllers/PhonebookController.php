<?php
class PhonebookController {

    /**
	 * Create new entity in db
	 * 
	 * @param Route_Command $command
	 * 
	 */
	public function add($command) {
		$mybook = new phonebook();
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
					$mybook->add_to_db($element);
					$url = dirname($_SERVER['SCRIPT_NAME']);
					Header("Location: $url");
					exit();
				}
			}
		}
		include(__DIR__ . "/../views/add.html.php");
	}

	/**
	 * Search records in db
	 * 
	 * @param Route_Command $command
	 * 
	 */	
	public function search($command) {
		$mybook = new phonebook();
		$this->command = $command;
		$flag = 0;
		$searchbook = array();
		$sort = "name";
		$direction = "up";
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
			if (sizeof($this->command->getParameters()) > 3) $element['phone'] = $parameters[3];
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
				$url .= '/search/'.$sort.'/'.$direction.'/'.$element['name'].'/'.$element['phone'];
				Header("Location: $url");
				exit();
			}
		}
		include(__DIR__ . "/../views/search.html.php");
	}

    /**
	 * Delete records from db
	 * 
	 * @param Route_Command $command
	 * 
	 */	
	public function delete($command) {
		$mybook = new phonebook();
		$this->command = $command;
		if (sizeof($this->command->getParameters()) > 0)
		{
			$parameters = $this->command->getParameters();
			$mybook->del_from_db($parameters[0]);
			$mybook->del_phones_by_name($parameters[0]);
		}
		$url = dirname($_SERVER['SCRIPT_NAME']);
		Header("Location: $url");
		exit();
	}
	
	/**
	 * Delete records from db
	 * 
	 * @param Route_Command $command
	 * 
	 */	
	public function delphone($command) {
		$mybook = new phonebook();
		$this->command = $command;
		if (sizeof($this->command->getParameters()) > 1)
		{
			$parameters = $this->command->getParameters();
			$mybook->del_phone($parameters[1]);
			$url = dirname($_SERVER['SCRIPT_NAME'])."/name/".$parameters[0];
			Header("Location: $url");
			exit();
		}
	}

	/**
	 * Show all records from db
	 * 
	 * @param Route_Command $command
	 * 
	 */	
	public function index($command) {
		$mybook = new phonebook();
		$this->command = $command;
		$sort = 'name';
		if (sizeof($this->command->getParameters()) > 0) {
			$parameters = $this->command->getParameters();
			if ($parameters[0] == "1") $sort = "phone";
		}
		$book = $mybook->get_sorted_data($sort, 'up');
		include __DIR__ . "/../views/main.html.php";
	}

	/**
	 * Sort records from db on main page
	 * 
	 * @param Route_Command $command
	 * 
	 */	
	public function tosort($command) {
		$mybook = new phonebook();
		$this->command = $command;
		$sort = 'name';
		$direction = 'up';
		if (sizeof($this->command->getParameters()) > 1) {
			$parameters = $this->command->getParameters();
			if ($parameters[0] == "phone") $sort = "phone";
			if ($parameters[1] == "down") $direction = "down";
		}
		$book = $mybook->get_sorted_data($sort, $direction);
		include(__DIR__ . "/../views/main.html.php");
	}

	/**
	 * Show profile with name and phones
	 * 
	 * @param Route_Command $command
	 * 
	 */	
	public function name($command) {
		$mybook = new phonebook();
		$emptyfields = false;
		$this->command = $command;
		if (sizeof($this->command->getParameters()) > 0) {
			$parameters = $this->command->getParameters();
			$name = $parameters[0];
			$record = $mybook->get_name_phone($name);
			$phones = $mybook->get_phones($name);
		}
		else {
			$url = dirname($_SERVER['SCRIPT_NAME']);
			Header("Location: $url");
			exit();
		}
		if (isset($_REQUEST['doAddPhone'])) {
			$element = $_REQUEST['element'];
			if (ini_get("magic_quotes_gpc"))
				$element = array_map('stripslashes', $element);
			$element['name'] = $name;
            if ($element['phone'] == "") {
				$emptyfields = true;
			} else {
				$mybook->add_phone($element);
				$url = dirname($_SERVER['SCRIPT_NAME']); 
				$url .= '/name/'.$element['name'];
				Header("Location: $url");
				exit();
			}
		}
		include(__DIR__ . "/../views/name.html.php");
}
}
?>
