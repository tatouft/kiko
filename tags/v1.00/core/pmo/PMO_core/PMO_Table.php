<?php

 /**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 


/**
 *  Table describe database table
 */

interface PMO_Table{
	/** return primary key of a table */
	public function getPk();
	
	/** return columns */
	public function getColumns();
	
	/** get name table */
	public function getTableName();
	
	/** set attribute */
	public function setAttribute($attributevalue);
	
	/** 
	 * Check if attribute is set 
	 * return TRUE or Exception
	 * */
	public function issetAttribute($attributename);
	
	/** set name table */
	public function setTableName($table);
	
}
?>
