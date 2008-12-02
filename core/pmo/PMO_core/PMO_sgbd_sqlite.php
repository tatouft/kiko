<?php

/**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 


require_once('PMO_sgbd_pdo.php');

class PMO_Sgbd_Sqlite extends PMO_Sgbd_Pdo {
	
	public function connectSgbd(array $authdb){
		self::$DB = new PDO($authdb['dsn']);
		self::$DB->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}
	
	public function getTableDesc($table) {
		$sql = sprintf('PRAGMA table_info(%s) ;', addslashes($table));
		$this->querySGBD($sql);
		
		foreach($this->result as $row){
			if(isset($row[1])){
				$Field = $row[1];
			}else{
				throw New Exception("Fatal Error: column doesn't exist");
			}
			
			if(isset($row[2]))
				$Type = $row[2];	

			if (eregi('auto_increment', $row[2]))
				$Extra = "auto_increment";
			else
				$Extra = "";	
			
			if(!empty($row[3]))
				$Null = "YES";
			else
				$Null = "NO";				

			if(isset($row[4]))
				$Default = $row[4];
			else
				$Default = "";	

			if(!empty($row[5]))
				$Key = "PRI";
			else
				$Key = "";
				
			$tmparray[] = array("Field" => $Field, 
								"Type" => $this->translateType($Type), 
								"Null" => $Null, 
								"Key"=>$Key, 
								"Default"=>$Default, 
								"Extra"=>$Extra);
		}
		return($tmparray);
	}
	
}
?>
