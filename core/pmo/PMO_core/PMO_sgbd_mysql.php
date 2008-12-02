<?php

/**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 


class PMO_Sgbd_Mysql extends PMO_MySgbd {

	public function __construct($mysqllink = NULL) {
		if(isset($mysqllink))
			$this->setDB($mysqllink);
	}

	public function connectSgbd(array $authdb){
		self::$DB = mysql_connect($authdb['host'], $authdb['user'], $authdb['pass']) or die(mysql_error());
		mysql_select_db($authdb['base'], self::$DB) or die(mysql_error());
	}

	public function __destruct() {
		@mysql_close(self::$DB);
	}

	public function querySGBD($query){
		try{
			$this->result = mysql_query($query) or die('Invalid query: ' . mysql_error());
		} catch (Exception $e){
			die($e->getMessage());
		}
		return TRUE;
	}

	public function fetchArray() {
		if (!empty($this->result))
			return mysql_fetch_assoc($this->result);
		return false;
	}

	public function getTableDesc($table) {
		$sql = sprintf('DESC %s', addslashes($table));
		$this->querySGBD($sql);
		
		while($dbresult = $this->fetchArray()){
			$tmparray[] = array("Field"=>$dbresult['Field'], 
								"Type" => $this->translateType($dbresult['Type']), 
								"Null" => $dbresult['Null'], 
								"Key"=>$dbresult['Key'], 
								"Default"=>$dbresult['Default'], 
								"Extra"=>$dbresult['Extra']);
		}
		return $tmparray;
	}
	
	public function getLastId() {
		return mysql_insert_id();
	}

}
?>
