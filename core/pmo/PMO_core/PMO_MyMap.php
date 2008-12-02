<?php

/**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 


require_once('PMO_Map.php');

class PMO_MyMap implements PMO_Map{
	protected $iterator = -1;
	protected $map = array();
		
	public function addLine($line){
		$this->map[] = $line;	
	}
	
	/** obsolete */
	public function getMapByObjectId($objectid){
		$map = new PMO_MyMap;
		foreach($this->map as $line)
			foreach($line as $object)
			if($object->getId() == $objectid)
				$map->addLine($line);

		return $map;
	}
	
	/** obsolete */
	public function getObjectById($objectid){
		foreach($this->map as $line)
			foreach($line as $object)
				if($object->getId() == $objectid)
					return $object;

		return FALSE;				
	}
	
	public function getMapByObject(PMO_Object $object){
		$map = new PMO_MyMap;
		foreach($this->map as $line)
			foreach($line as $currentobject)
			if($currentobject === $object)
				$map->addLine($line);

		return $map;
	}
	
	public function getMapLinked(PMO_Object $object){
		return $this->getMapByObject($object);
	}
	
	public function getMapByValue($tablename, $attribute, $value){
		foreach($this->map as $line)
					if($line[$tablename]->getAttribute($attribute) == $value)
						return $this->getMapByObject($line[$tablename]);		
							
		throw new Exception("Error: No object found");
	}
	
	public function getMapByTable($tablename){
		$newmap = new PMO_MyMap();
		foreach($this->map as $line){
			if(!isset($line[$tablename]))
				throw new Exception("Error: Tablename is undefine");
				
			$array[$tablename] = $line[$tablename];
			$newmap->addLine($array);
		}
		return $newmap;
	}
	
	public function getMap(){
		if(isset($this->map))
			return $this->map;
			
		throw new Exception("Error: Map is undefine");
	}
	
	public function fetchMap(){		
		$this->iterator++;
		if($this->iterator < count($this->map)){
			return($this->map[$this->iterator]);
		}
		$this->iterator = -1;
	}
	
	public function numRows(){
		return count($this->map);
	}
	
	public function getObjectByValue($tablename, $attribute, $value){
		foreach($this->map as $line){
			if(!isset($line[$tablename]))
				throw new Exception("Error: Object is undefine");
			
			if(! strcmp ($line[$tablename]->getAttribute($attribute), $value))
				return $line[$tablename];
		}
		throw new Exception("Error: No object found");
	}
}
?>
