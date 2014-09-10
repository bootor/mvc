<?php
class PhonebookController {
	
	public function add($command) {
		global $commandResult, $mybook;
		$this->command = $command;
		include "uplinks.html";
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
			if (@$_REQUEST['doAdd']) {
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
		include "addform.html";
		if ($emptyfields == true)
			include "addemptyfields.html";
	}

	public function search($command) {
		global $commandResult, $mybook;
		$this->command = $command;
		$flag = 0;
		$searchbook = array();
		$sort = "name";
		$direction = 'up';
		$emptyfields = true;
		include "uplinks.html";
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
				$emptyfields = false;
				$flag = 1;
				$mybook->get_search_data($element);
			}
		}
		$searchbook = $mybook->get_searched_data($sort, $direction);
		include "searchform.html";
		echo "<hr>";
		include "searchsortlinks.html";
		echo "<table>";
		foreach($searchbook as $element) {
		echo "<tr><td><b>".htmlspecialchars($element['name'])."</b>:</td><td><i>".htmlspecialchars($element['phone'])."</i></td></tr>";
		}
		echo "</table><hr>";
		
		if (count($searchbook) === 0 AND $flag === 1) echo "No match founded...<br><hr>";
		
		if (@$_REQUEST['doSearch']) {
			$element = $_REQUEST['element'];
			if (ini_get("magic_quotes_gpc"))
				$element = array_map('stripslashes', $element);
			if (($element['name'] == "") AND ($element['phone'] == "")) {
				echo "<hr>Empty fields. Search is impossible.<hr>";
			} else {
				$url = dirname($_SERVER['SCRIPT_NAME']); 
				$url .= '/search/'.$element['name'].'/'.$element['phone'];
				Header("Location: $url");
				exit();
			}
		}
	}

	public function delete($command) {
		global $commandResult, $mybook;
		$this->command = $command;
		include "uplinks.html";
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
		global $commandResult, $mybook;
		$this->command = $command;
		$sort = 'name';
		if (sizeof($this->command->getParameters()) > 0) {
			$parameters = $this->command->getParameters();
			if ($parameters[0] == "1") $sort = "phone";
		}
		$book = $mybook->get_sorted_data($sort, 'up');
		include "uplinks.html";
		include "sortlinks.html";
		echo "<table>";
		foreach($book as $element) {
			echo "<tr><td><b>".htmlspecialchars($element['name'])."</b>:</td><td><i>".htmlspecialchars($element['phone']);
			$url = $_SERVER['SCRIPT_NAME']."/delete/".$element['id'];
			echo "</i></td><td><a href='$url'>[Delete]</a></td></tr>";
		}
		echo "</table><hr>";
	}

	public function tosort($command) {
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
		include "uplinks.html";
		include "sortlinks.html";
		echo "<table>";
		foreach($book as $element) {
			echo "<tr><td><b>".htmlspecialchars($element['name'])."</b>:</td><td><i>".htmlspecialchars($element['phone']);
			$url = $_SERVER['SCRIPT_NAME']."/delete/".$element['id'];
			echo "</i></td><td><a href='$url'>[Delete]</a></td></tr>";
		}
		echo "</table><hr>";
	}
}
?>
