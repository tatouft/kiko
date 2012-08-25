<?php

/**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 


class PMO_Sgbd_Pdo extends PMO_MySgbd {

	public function __construct(PDO $pdo = NULL) {
		if(isset($pdo))
			$this->setDB($pdo);
	}
	
	public function connectSgbd(array $authdb){
		self::$DB = new PDO("$authdb[pdodriver]:host=$authdb[host];dbname=$authdb[base]", $authdb[user], $authdb[pass]);
		self::$DB->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}
	
	public function __destruct() {
		self::$DB = NULL;
	}

	public function querySGBD($query){
		try{
			$this->result = self::$DB->prepare($query);
			$this->result->execute();
		}catch (PDOException $e) {
			die($e->getMessage());
		}
		return TRUE;
	}

	public function fetchArray() {
		if (!empty($this->result))
		{
			$tmp = $this->result->fetch(PDO::FETCH_ASSOC);
			return $tmp; 
		}
			
		return FALSE;
	}

	public function getTableDesc($table) {
		$sql = sprintf('DESC %s ;', addslashes($table));
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
		return self::$DB->lastInsertId();
	}

}
?>
