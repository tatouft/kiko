<?php

/**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 

/**
 * PMO_MapTable is an array 
 * that contains PMO_Table objects.
 */
 
interface PMO_MapTable{
	
	
	 /** Add an object PMO_Table in the array  */
	public function addTable(PMO_Table $table);
	
	/**
	 *  Return the data array 
	 * */
	public function getMap();

	/** 
	 * Retrieve a PMO_Table in the array
	 * criterions
	 * PMO_Table->$tablename = $table
	 * */
	public function getTableByValue($table);

}

?>
