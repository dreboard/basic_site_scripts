<?php
namespace App\Main;

use PDO;
use PDOException;

class Database extends PDO {

	private $engine;
	private $host;
	private $database;
	private $user;
	private $pass;

	public function __construct(){
		error_log(xdebug_call_class());
		$this->engine = 'mysql';
		$this->host = 'localhost';
		$this->database = 'server1';
		$this->user = 'root';
		$this->pass = '';
		$dns = $this->engine.':dbname='.$this->database.";host=".$this->host;
		parent::__construct( $dns, $this->user, $this->pass );
	}
}