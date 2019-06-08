<?php

class Database{

	private $host = "localhost";
	private $user = "root";
	private $pass = "";
	private $dbname = "task";

	public function connect(){
		$conn = null;

		try {
			
			$conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $conn;

		} catch (Exception $e) {
			echo "ERROR: ".$e->getMessage();
			echo "LINEA: ".$e->getLine();
			exit;
		}
	}
}