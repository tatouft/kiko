<?php

/**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 


class PMO_Sgbd_Pgsql extends PMO_MySgbd {

	public function __construct($pgsqllink = NULL) {
		if(isset($pgsqllink))
			$this->setDb($pgsqllink);
	}
	
	public function connectSgbd(array $authdb){
			self::$DB = pg_connect("host={$authdb[host]} dbname={$authdb[base]} user={$authdb[user]} password={$authdb[pass]}")
			or die(pg_last_error());
	}
	
	public function __destruct() {
		@pg_close(self::$DB);
	}

	public function querySGBD($query){
		$this->result = pg_query($query) or die('Invalid query: ' . pg_last_error());
		return true;
	}

	public function fetchArray() {
		if (!empty($this->result))
			return pg_fetch_assoc($this->result);
		return false;
	}

	public function getTableDesc($table) {
		$this->result = pg_query_params('
			SELECT f.column_name AS "Field",
				CASE 
					WHEN c.contype=\'p\' THEN \'PRI\'
					ELSE \'NULL\'
				END AS "Key"
			FROM
				information_schema.columns AS f
				LEFT JOIN (
					information_schema.constraint_column_usage cu
						JOIN pg_catalog.pg_constraint AS c ON (cu.constraint_name = c.conname))
				ON (f.column_name=cu.column_name AND f.table_name=cu.table_name)
			WHERE
				f.table_name = $1', array(addslashes($table)))
			or die('Invalid query: ' . pg_last_error());
		
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
		
	}
	
}
?>
