<?php

 /**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 


/**
 *  A SGBD Request Parser
 */

interface PMO_Parser{

	/** 
	 * Parse SQL Request
	 * Retrieve Table names and Fields names
	 * in 2 arrays 
	 * */
	public function parseRequest($string);

	/** 
	 * Return array of fields name
	 * */
	public function getFields();

	/**
	 *  Return array of tables name 
	 * */
	public function getTables();
}
?>
