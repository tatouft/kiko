<?php

/**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 

/**
 * MapHash is a an array that contains 
 * PMO_Object and their Hashs
 *  */

interface PMO_MapHash{
	
	/** 
	 * Add a PMO_Object and its hash in array 
	 * */
	public function addHash($value, $object);
	
	/**
	 *  Retrieve a PMO_Object by its hash  
	 * */
	public function getHash($value);
	
} 
?>
