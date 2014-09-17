<?php
class phonebook {
	
	private $user = "bootor";
	private $pass = "fuck off";
	private $db   = "mydb";
	private $tblname = "phonebook";
	private $searchtbl = "phonesearch";
	private $phonestbl = "phonestbl";
	private $mysqli;

	public function __construct() {
		
		$this->mysqli = new mysqli("localhost", $this->user, $this->pass, $this->db);
		if (mysqli_connect_errno()) { 
			printf("Connection impossible: %s\n", mysqli_connect_error()); 
			exit(); 
		}
		
		if ($stmt = $this->mysqli->prepare("CREATE TABLE IF NOT EXISTS `$this->tblname`
			(id INT AUTO_INCREMENT PRIMARY KEY,
			 name VARCHAR(60),
			 phone VARCHAR(15))")) { 
			$stmt->execute(); 
			$stmt->close();
		}
		
		if ($stmt = $this->mysqli->prepare("CREATE TABLE IF NOT EXISTS `$this->searchtbl`
			(id INT AUTO_INCREMENT PRIMARY KEY,
			 name VARCHAR(60),
			 phone VARCHAR(15))")) { 
			$stmt->execute(); 
			$stmt->close();
		}
		
		if ($stmt = $this->mysqli->prepare("CREATE TABLE IF NOT EXISTS `$this->phonestbl`
			(id INT AUTO_INCREMENT PRIMARY KEY,
			 name VARCHAR(60),
			 phone VARCHAR(15))")) { 
			$stmt->execute(); 
			$stmt->close();
		}
	}	

	public function __destruct() {
		$this->mysqli->close();
	}

	public function add_to_db($element) {
		if ($stmt = $this->mysqli->prepare("INSERT INTO `$this->tblname` (`name`, `phone`) VALUES (?, ?)")) {
			$stmt->bind_param("ss", $element['name'], $element['phone']);
			$stmt->execute();
			$stmt->close(); 
		}
	}

	public function del_from_db($name) {
		if ($stmt = $this->mysqli->prepare("DELETE FROM `$this->tblname` WHERE name=?")) {
			$stmt->bind_param("s", $name);
			$stmt->execute();
			$stmt->close();
		}
	}
	
	public function del_phones_by_name($name) {
		if ($stmt = $this->mysqli->prepare("DELETE FROM `$this->phonestbl` WHERE name=?")) {
			$stmt->bind_param("s", $name);
			$stmt->execute();
			$stmt->close();
		}
	}

	public function get_sorted_data($sort, $direction) {
		$direction == "down" ? $sqladdon = ' DESC' : $sqladdon = '';
		$sort == 'phone' ? $sortflag = 'phone' : $sortflag = 'name';
		if ($stmt = $this->mysqli->prepare("SELECT * FROM `$this->tblname` ORDER BY ".$sortflag.$sqladdon)) {
			$stmt->execute();
			$stmt->bind_result($id, $name, $phone);
			$book = array();
			while ($stmt->fetch()) {
				$book[]=['id' => $id, 'name' => $name, 'phone' => $phone];
			}
			$stmt->close();
			return $book;
		}
	}

	public function get_searched_data($sort, $direction) {
		$this->direction = $direction;
        $this->sort = $sort;
        $this->direction == "down" ? $sqladdon = ' DESC' : $sqladdon = '';
		$this->sort == 'phone' ? $sortflag = 'phone' : $sortflag = 'name';
		if ($stmt = $this->mysqli->prepare("SELECT * FROM `$this->searchtbl` ORDER BY ".$sortflag.$sqladdon)) {
			$stmt->execute();
			$stmt->bind_result($id, $name, $phone);
			$book = array();
			while ($stmt->fetch()) {
				$book[]=['id' => $id, 'name' => $name, 'phone' => $phone];
			}
			$stmt->close();
			return $book;
		}
	}

	public function get_search_data($element) {
		$searchbook = [];
		if ($element['name'] != "" AND $element['phone'] != "") {
			if ($stmt = $this->mysqli->prepare("SELECT * FROM `$this->tblname` WHERE `name` LIKE CONCAT('%', ?, '%') AND `phone` LIKE CONCAT('%', ?, '%')")) {
				$stmt->bind_param("ss", $element['name'], $element['phone']);
				$stmt->execute(); 
				$stmt->bind_result($id, $name, $phone);
				while ($stmt->fetch()) {
					$searchbook[]=['id' => $id, 'name' => $name, 'phone' => $phone];
				}
				$stmt->close(); 
			}
		} else {
			if ($stmt = $this->mysqli->prepare("SELECT * FROM `$this->tblname` WHERE (`name` LIKE CONCAT('%', ?, '%') AND (? != '')) OR (`phone` LIKE CONCAT('%', ?, '%') AND (? != ''))")) {
				$stmt->bind_param("ssss", $element['name'], $element['name'], $element['phone'], $element['phone']);
				$stmt->execute(); 
				$stmt->bind_result($id, $name, $phone);
				while ($stmt->fetch()) {
					$searchbook[]=['id' => $id, 'name' => $name, 'phone' => $phone];
				}
				$stmt->close(); 
			}
		}
		if ($stmt = $this->mysqli->prepare("TRUNCATE `$this->searchtbl`")) { 
			$stmt->execute();
			$stmt->close();
		}
		foreach ($searchbook as $element) {
			if ($stmt = $this->mysqli->prepare("INSERT INTO `$this->searchtbl` (`name`, `phone`) VALUES (?, ?)")) {
				$stmt->bind_param("ss", $element['name'], $element['phone']);
				$stmt->execute();
				$stmt->close();
			}
		}
	}

	public function get_name_phone($name) {
		if ($stmt = $this->mysqli->prepare("SELECT * FROM `$this->tblname` WHERE name=?")) {
			$stmt->bind_param("s", $name);
			$stmt->execute();
			$stmt->bind_result($id, $name, $phone);
			$record = array();
			while ($stmt->fetch()) {
				$record[]=['id' => $id, 'name' => $name, 'phone' => $phone];
			}
			$stmt->close();
			return $record;
		}
	}

	public function get_phones($name) {
		if ($stmt = $this->mysqli->prepare("SELECT * FROM `$this->phonestbl` WHERE name=?")) {
			$stmt->bind_param("s", $name);
			$stmt->execute();
			$stmt->bind_result($id, $name, $phone);
			$phones = array();
			while ($stmt->fetch()) {
				$phones[]=['id' => $id, 'name'=> $name, 'phone' => $phone];
			}
			$stmt->close();
			return $phones;
		}
	}
	
	public function add_phone($element) {
		if ($stmt = $this->mysqli->prepare("INSERT INTO `$this->phonestbl` (`name`, `phone`) VALUES (?, ?)")) {
			$stmt->bind_param("ss", $element['name'], $element['phone']);
			$stmt->execute();
			$stmt->close();
		}
	}

	public function del_phone($delid) {
		if ($stmt = $this->mysqli->prepare("DELETE FROM `$this->phonestbl` WHERE id=?")) { 
			$stmt->bind_param("i", $delid);
			$stmt->execute();
			$stmt->close();
		}
	}

}
?>
