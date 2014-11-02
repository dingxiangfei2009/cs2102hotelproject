<?php
// encapuslation for application data connection
class Connector {
	private $conn;
	function __construct() {
		$this->conn = mysqli_connect('p:localhost', 'userapp', 'CS@!)@', 'cs2102proj') or die('Server down\n\r'.mysqli_error($conn));
	}
	public function createPreparedStatement($query) {
		return $this->conn->prepare($query);
	}
}
?>
