<?php

/**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 


require_once('PMO_Controller.php');
require_once('PMO_MyObject.php');
require_once('PMO_MyMap.php');
require_once('PMO_MyMapHash.php');
require_once('PMO_MyParser.php');
require_once('PMO_MyMapTable.php');
require_once('PMO_MyTable.php');
require_once('PMO_MySgbd.php');


class PMO_MyController implements PMO_Controller {
	private $map_objects;
	private $map_tables;
	private $map_hash;
	private static $SGBD;

	public function __construct($object = NULL) {
		if(!isset(self::$SGBD))
			self::$SGBD = PMO_MySgbd::factorySgbd($object);
	}

	private function populateMapTables($query){
		$this->map_tables = new PMO_MyMapTable();
		$parsersql = new PMO_MyParser();
		$parsersql->parseRequest($query);
		$tables = $parsersql->getTables();
		
		foreach($tables as $table){
			$objecttable = new PMO_MyTable($table);
			$this->map_tables->addTable($objecttable);
		}		
	}

	private function populateMapObjects($query){
		$this->map_objects = new PMO_MyMap();
		$this->map_hash = new PMO_MyMapHash();
		
		self::$SGBD->querySGBD($query);
		while($db_result = self::$SGBD->fetchArray()) {
			$tmp_array = array();
			foreach($this->map_tables->getMap() as $table) {
				$tablename = $table->getTableName();
				$tablepk = "";
				$tmp = $table->getPk();
				foreach($tmp as $pk)
					$tablepk = $tablepk.$pk.$db_result[$pk];
				
				try{
					$currentobject = $this->map_hash->getHash($tablename.$tablepk);
				}catch(Exception $e){
					$currentobject = PMO_MyObject::internalfactory($table);
					$tablefields = $table->getColumns(); 
					foreach( $tablefields as $field){
						$currentobject->setAttribute($field, $db_result[$field]);
					}
					$this->map_hash->addHash($tablename.$tablepk, $currentobject);
				}
				$tmp_array[$tablename] =  $currentobject;
			}
			$this->map_objects->addLine($tmp_array);
		}

		return($this->map_objects);
	}

	public function getMapObjects(){
		if(isset($this->map_objects))
			return $this->map_objects;
			
		throw new Exception("Error: Map is empty");
	}
	
	public function queryController($query){
		try{
			$this->populateMapTables($query);
			$this->populateMapObjects($query);
		}catch(Exception $e){
			die($e->getMessage());
		}
		return $this->getMapObjects();
	}
}
?>
