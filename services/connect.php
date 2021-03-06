<?php
// encapuslation for application data connection
require_once('error.php');
class Connector {
	private $conn;
	function __construct() {
		$this->conn = mysqli_connect('p:localhost', 'userapp', 'CS@!)@', 'cs2102proj')
			or die('Server down\n\r'.mysqli_error($this->conn));
	}
	public function createPreparedStatement($query) {
		$stmt = $this->conn->prepare($query);
		if (!$stmt) {
			report(mysqli_error($this->conn));
		}
		return $stmt;
	}
	public function getError() {
		return $this->conn->error;
	}
	public function getAffectedRows() {
		return $this->conn->affected_rows;
	}
}
?>
