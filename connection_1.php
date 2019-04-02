<?php
Class dbObj{
	/* Database connection start */
	var $dbhost = "us-cdbr-iron-east-03.cleardb.net";
	var $username = "bffc36c60eb244";
	var $password = "0f4f564c";
	var $dbname = "heroku_965df3cd76177bf";
	var $conn;
	function getConnstring() {
		$con = mysqli_connect($this->dbhost, $this->username, $this->password, $this->dbname) or die("Connection failed: " . mysqli_connect_error());

		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		} else {
			$this->conn = $con;
		}
		return $this->conn;
	}
}
?>
