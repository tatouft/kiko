<?php

/**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 


require_once('PMO_MapTable.php');

class PMO_MyMapTable implements PMO_MapTable{
	protected $MapTable = array();

	public function addTable(PMO_Table $object){
		$this->MapTable[$object->getTableName()] = $object;
	}
		
	public function getMap(){
		if(isset($this->MapTable))
			return $this->MapTable;
			
		throw new Exception("Error: Map is undefine");	
	}
	
	public function getTableByValue($tablename){
		if(isset($this->MapTable[$tablename]))
			return $this->MapTable[$tablename];
		
		throw new Exception("Error: Table is undefine");
	}
	
}

?>
