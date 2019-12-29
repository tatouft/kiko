<?php

/**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 


require_once('PMO_MapHash.php');

class PMO_MyMapHash implements PMO_MapHash{
	protected $maphash = array();
	
	public function addHash($value, $object){
		$hash = md5($value);
		$this->maphash[$hash][] = $object;
	}
	
	public function getHash($value){
		$hash = md5($value);
		if(isset($this->maphash[$hash][0])){		
			return $this->maphash[$hash][0];
		}
		
		throw new Exception("Error: no hash found");
	}
	
	/** obsolete */
	public function getObjectByValue($table, $attribute, $value){
		$hash = md5($table.$attribute.$value);
		if(isset($this->maphash[$hash]))
			return $this->maphash[$hash];
			
		return FALSE;
	}
} 
?>
