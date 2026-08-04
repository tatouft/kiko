<?php

/**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 


/**
 * Object is a generical interface for entities (Mvc)
 */

interface PMO_Object{

	/** 
	 * Return global counter (STATIC)
	 * Global counter permits to give an unique id
	 * to PMO_Object at instanciation time. 
	 * */
	public function getCounter();
	
	/**
	 *  Obsolete should not be use anymore
	 *  Return objectid attribute
	 *  ObjectId is unique and identify the PMO_Object 
	 * */
	public function getId();
	
	/**
	 *  Return PMO_Table linked to PMO_Object 
	 * */
	public function getTable();

	/** 
	 * Return an array : list all attributes of the PMO_Object 
	 * */
	public function getListAttribute();
	
	/** 
	 * Return an array of attributes & value of the objet 
	 * */
	public function getObjectAttribute();

	/** 
	 * Return value of 1 attribute $attributename 
	 * */
	public function getAttribute($attributename);

	/** 
	 * Set attribute $attributename with $attributevalue
	 * Return an exception if attribute doesn't exist 
	 * */
	public function setAttribute($attributename, $attributevalue);
	
	/** 
	 * Delete the row corresponding to PMO_Object 
	 * from PMO_Table in database 
	 * */
	public function delete();
	
	/** 
	 * Commit PMO_Object in PMO_Table linked 
	 * in database
	 * */
	public function commit();
}
?>
