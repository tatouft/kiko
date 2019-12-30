<?php

/**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 

require_once('PMO_Object.php');

class PMO_MyObject implements PMO_Object{
	
	protected static $COUNTER = -1;
	protected $object_id;
	protected $object_table;
	protected $object_attribute = array();
	protected $object_linked = array();
	protected $object_new = 0;

	function __autoload($tablename) {
    	require_once("class_loader/class_{$tablename}.php");
	}

	protected function __construct(PMO_Table $table){
		self::$COUNTER++;
		$this->initId(self::$COUNTER); 				
		$this->initTable($table);
	}
	
	public static function internalfactory(PMO_Table $table){
		$tablename = $table->getTableName();
		if (class_exists($tablename)){
			$object = new $tablename($table);	
		}else{
			$object = new PMO_MyObject($table);
		}
		return $object;
	}
	
	public static function factory($tablename){
		$table = new PMO_MyTable($tablename);
		$object = PMO_MyObject::internalfactory($table);
		$object->initObjectNew(1);
		return $object;
	}
	
	public static function override($tablename){
		$table = new PMO_MyTable($tablename);
		$object = PMO_MyObject::internalfactory($table);
		return $object;
	}
	
	public function getCounter(){
		return self::$COUNTER;
	}
	
	public function getId(){
		if(isset($this->object_id))
			return $this->object_id;
		
		throw new Exception("Error: Object id is undefine");
	}
	
	public function getTable(){
			if(isset($this->object_table))
				return $this->object_table;
		
		throw new Exception("Error: Object is undefine");
	}
		
	private function initId($id){
		$this->object_id = $id;
	}
	
	private function initTable($table){
		$this->object_table = $table;
	}
	
	private function initObjectNew($value){
		$this->object_new = $value;
	}

	public function getObjectAttribute(){
		if(isset($this->object_attribute))
			return $this->object_attribute;
		
		throw new Exception("Error: No data found in object");
	}
		
	public function getListAttribute(){
		if(isset($this->object_attribute)){
			foreach($this->object_attribute as $attributename=>$attributevalue)
				$array[] = $attributename;
				
			return $array;
		}
		throw new Exception("Error: No data found in object");
	}

	public function getAttribute($attributename) {
		// FJA
		if(isset($this->object_attribute[$attributename])) {
			//echo("<br/>Attr: " . $this->object_attribute[$attributename]);
			return stripslashes($this->object_attribute[$attributename]);
		}
		else
			return NULL;
				
		throw new Exception("Error: attribute doesn't exist");
	}

	public function toXml($encoding){
		$out = "<?xml version=\"1.0\" encoding=\"$encoding\"?>\r"; //"
		$out .= "<attributes>\r";

		foreach($this->object_attribute as $key=>$value)
			$out .= "<{$key}>$value</{$key}>\r";

		$out .= "</attributes>\r";
		return $out;
	}
	
	public function setAttribute($attributename, $attributevalue) {	
			//if(!isset($attributevalue))
			//	throw new Exception("Error: attribute value is undefine: " . $attributename);
			if($this->object_table->issetAttribute($attributename))
			{
				// FJA
				if(!isset($attributevalue))
					$this->object_attribute[$attributename] = NULL;
				else{
					$this->object_attribute[$attributename] = addslashes($attributevalue);
				}
			}
			else
				throw new Exception("Error: attribute value is undefined");
				
		return TRUE;
	}

	public function __set($attributename, $attributevalue) {
			$this->setAttribute($attributename, $attributevalue);
	}
	
	public function __get($attributename) {
			if($this->getAttribute($attributename))
				return $this->getAttribute($attributename);

			// FJA
			if(isset($this->object_linked[$attributename]))
				return $this->object_linked[$attributename];
			else
				return NULL;

		throw new Exception("Error: attribute value is undefine");;
	}

	private function initAttribute($attributename, $attributevalue) {
		$this->object_attribute[$attributename] = stripslashes($attributevalue);
	}
	
	public function load(){
		$SGBD = PMO_MySgbd::factorySgbd();
		$SGBD->loadObject($this);
		$this->initObjectNew(0);	
	}
	
	public function delete(){
		$SGBD = PMO_MySgbd::factorySgbd();
		$SGBD->deleteObject($this);
	}
	
	public function commit(){
		$SGBD = PMO_MySgbd::factorySgbd();
		if($this->object_new == 0){
			$SGBD->updateObject($this);
		} else {
			$SGBD->insertObject($this);
			$this->object_new = 0;
		}
	}

}
?>
