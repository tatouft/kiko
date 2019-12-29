<?php

/**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 


require_once('PMO_Table.php');

class PMO_MyTable implements PMO_Table{
	
	protected static $SGBD;
	protected $table_name;
	protected $table_pk = array();
	protected $table_attribute = array();
	
	public function __construct($tablename){
		if(!isset(self::$SGBD))
			self::$SGBD= PMO_MySgbd::factorySgbd();
		$this->populate($tablename);	
	}
	
	private function populate($tablename){
			$this->setTableName($tablename);
			$tmparray = self::$SGBD->getTableDesc($tablename);
			foreach($tmparray as $attributevalue)
				$this->setAttribute($attributevalue);
			$this->setPk($this->searchPk()); 		
	}
	
	public function getTableName(){
		if(isset($this->table_name))
			return $this->table_name;
		
		throw new Exception("Error: no tablename");
	}
	
	public function getPk(){
		if(isset($this->table_pk))
			return $this->table_pk;
			
		throw new Exception("Error: undefine primary key");
	}
	
	public function setPk($attributename){
		$this->table_pk = $attributename;
		return true;
	}
	
	public function searchPk(){
		foreach($this->table_attribute as $attributename=>$attributevalue){
			if(! strcmp ($attributevalue['Key'], 'PRI')){
				$array[] = $attributename;
			}
		}

		if(isset($array)){
			return $array;
		}else{
			throw new Exception("Error: undefined primary key");
		}		
	}
	
	public function setTableName($table){
		$this->table_name = $table;
		return TRUE;
	}
	
	public function getColumns(){
		if(!isset($this->table_attribute))
			throw new Exception("Error: attributes table are undefine");
		
		foreach($this->table_attribute as $name=> $value)
			$array[] = $name;
			
			return $array;
	}
	
	public function getAttribute($attributename){
		if(isset($this->table_attribute[$attributename]))
			return $this->table_attribute[$attributename];
		
		throw new Exception("Error: Attribute Table is undefine");
	}
	
	public function issetAttribute($attributename){
		if(isset($this->table_attribute[$attributename]))
			return TRUE;
		
		throw new Exception("Error: attribute not exists");
	}
	
	public function setAttribute($attributevalue){
		$this->table_attribute[$attributevalue['Field']] = $attributevalue;
		return TRUE;
	}
}

?>
