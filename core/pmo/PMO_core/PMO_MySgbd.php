<?php

/**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 


require_once("PMO_Sgbd.php");

abstract class PMO_MySGBD implements PMO_Sgbd {
	
	protected static $DB;
	protected $result;
	protected static $DBCLASS;

	public static function factorySgbd($object = NULL){
		if(!isset(self::$DBCLASS)){
			$driverz = "";
			$hostz = "";
			$basez = "";
			$userz = "";
			$passz = "";
			$dsn = "";
			
			require('config.php');
			$authdb = array("driver"=>$driverz, 
							"host"=>$hostz, 
							"base"=>$basez, 
							"user"=>$userz, 
							"pass"=>$passz, 
							"dsn"=>$dsn);
			
			switch($authdb['driver']){
				case 'sqlite':
						require_once("PMO_sgbd_sqlite.php");
						self::$DBCLASS = new Pmo_sgbd_sqlite($object);
						if(!isset($object))
							self::$DBCLASS->connectSgbd($authdb);
						break;

				case 'mysql':
						require_once("PMO_sgbd_mysql.php");
						self::$DBCLASS = new Pmo_sgbd_mysql($object);
						if(!isset($object))
							self::$DBCLASS->connectSgbd($authdb);
						break;
						
				case 'pgsql':
						require_once("PMO_sgbd_pgsql.php");
						self::$DBCLASS = new Pmo_sgbd_pgsql($object);
						if(!isset($object))
							self::$DBCLASS->connectSgbd($authdb);
						break;
						
				default:
						require_once("PMO_sgbd_pdo.php");
						self::$DBCLASS = new Pmo_sgbd_pdo($object);
						if(!isset($object))
							self::$DBCLASS->connectSgbd($authdb);
						break;
				
			}
		}
		return self::$DBCLASS;
	}

	public function getDB(){
		return self::$DB;
	}
	
	public function setDB($link){
		self::$DB = $link;
	}

	protected function translateType($type){
		if (preg_match('~int~i', $type)) {
    		return "int";
		}
		if (preg_match('~float~i', $type)) {
    		return "float";
		}
		if (preg_match('~blob~i', $type)) {
    		return "alnum";
		}
		if (preg_match('~text~i', $type)) {
    		return "alnum";
		}
		if (preg_match('~char~i', $type)) {
    		return "alnum";
		}			
		if (preg_match('~date~i', $type)) {
    		return "date";
		}
		if (preg_match('~time~i', $type)) {
    		return "date";
		}
		if (preg_match('~double~i', $type)) {
    		return "int";
		}
		return "alnum";
	}

	public function loadObject(PMO_Object $object){
		$querypk = "";
		$objectAttributes = $object->getObjectAttribute();
		$objectTable = $object->getTable();
		$tablepk = $objectTable->getPk();
		if(!isset($tablepk))
			throw new Exception("Error: Table doesn't have Primay Key");
			
		foreach( $tablepk as $pk){
			if(isset($objectAttributes[$pk]))
				$querypk .= " AND {$pk}=\"{$objectAttributes[$pk]}\"";
			else
				throw new Exception("Error: A primary key attribute is undefine");
		}			
		
		$query = "SELECT * FROM ".$objectTable->getTableName()." WHERE ".substr($querypk, 5, strlen($querypk)).";";
		$this->querySGBD($query);
		$result = $this->fetchArray();
		
		$tablefields = $objectTable->getColumns(); 
		foreach( $tablefields as $field){
			$object->setAttribute($field, stripslashes($result[$field]));
		}
	}
	
	public function deleteObject(PMO_Object $object){
		$querypk = "";
		$objectAttributes = $object->getObjectAttribute();
		$objectTable = $object->getTable();
		$tablepk = $objectTable->getPk();
		if(!isset($tablepk))
			throw new Exception("Error: Table doesn't have Primay Key");
			
		foreach( $tablepk as $pk)
			$querypk .= " AND {$pk}=\"{$objectAttributes[$pk]}\"";

		$query = "DELETE FROM {$objectTable->getTableName()} WHERE ".substr($querypk, 5, strlen($querypk)).";";
		$this->querySGBD($query);
		return TRUE;
	}
	
	public function updateObject(PMO_Object $object){
		$queryfield = "";
		$querypk = "";
		$objectAttributes = $object->getObjectAttribute();
		$objectTable = $object->getTable();

		foreach ($objectAttributes as $columns=>$value){
			$tablepk = $objectTable->getPk();
			if(!isset($tablepk))
				throw new Exception("Error: Table doesn't have primary key");
					
			foreach( $tablepk as $pk)
				if($pk == $columns){
					$querypk .= " AND {$pk}=\"{$value}\"";
				}elseif(isset($value)){
					$queryfield .= ",{$columns}=\"{$value}\"";
				}
		}

		$query = "UPDATE {$objectTable->getTableName()} SET ".substr($queryfield, 1, strlen($queryfield))." WHERE ".substr($querypk, 5, strlen($querypk)).";";
		//echo("<br/>Update: {$query}");
		$this->querySGBD($query);
		return TRUE;		
	}
	
	public function insertObject(PMO_Object $object){
		$queryfield = "";
		$value = "";
		$objectAttributes = $object->getObjectAttribute();
		$objectTable = $object->getTable();
		$tablecolumns = $objectTable->getColumns();
			
		if(!isset($tablecolumns))
			throw new Exception("Error: Object PMO_MyTable doesn't contain columns");
		
		foreach($tablecolumns as $field){
				if(isset($objectAttributes[$field])){
					$queryfield .= ",{$field}";
					$value .= ",\"{$objectAttributes[$field]}\"";
				}
		}
			
		$value = substr($value, 1, strlen($value));
		$queryfield = substr($queryfield, 1, strlen($queryfield));
		$query = "INSERT INTO {$objectTable->getTableName()}($queryfield) VALUES($value);";
		$this->querySGBD($query);
		return TRUE;
	}	
	
}
?>
