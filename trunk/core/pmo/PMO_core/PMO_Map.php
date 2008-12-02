<?php


/**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 

/**
 * PMO_Map is a array of PMO_Objects. 
 * Each row of array contains as many PMO_Objects
 * as there is table concerns by the SQL Request
 */
 
interface PMO_Map{
	
	/** Add an array of objects to map */
	public function addLine($line);
	
	/** 
	 * Obsolete you should not use this methode anymore
	 * Build a map of PMO_Object in relation with their objectid
	 *  */
	public function getMapByObjectId($objectid);

	/**  
	 * Obsolete you should not use this methode anymore 
	 * Return a PMO_Object from its objectid 
	 * */
	public function getObjectById($objectid);
	
	/**
	 * Build a PMO_Map that contains PMO_Object 
	 * linked by relation to one PMO_Object
	 */
	public function getMapLinked(PMO_Object $object);
	
	/**
	 * Build a PMO_Map that contains PMO_Object 
	 * linked by relation with one PMO_Object
	 */
	public function getMapByObject(PMO_Object $object);
	
	/**
	 * Build a PMO_Map that contains PMO_Object
	 * linked by relation with PMO_Table->$tablename
	 */
	public function getMapByTable($tablename);
	
	/** 
	* Return number of rows for this PMO_Map
	*/
	public function numRows();
	
	/**
	 * Return one row of PMO_MyMap until the end of the PMO_Map 
	 * and return nothing where thers is no row anymore.
	 */
	public function fetchMap();
	
	/**
	 *  Return directly the Map data of PMO_Map 
	 * */
	public function getMap();
	
	/** 
	 * 	Build a new PMO_Map from this PMO_Map
	 *  that contains rows relative to Pmo_Object
	 *  criterions are
	 *  PMO_Table->$tablename
	 *  PMO_Object->$attribute=$value
	 *  */
	public function getMapByValue($tablename, $attribute, $value);

	/**
	 * Retrieve a PMO_Object in the PMO_Map 
	 * criterions are
	 * PMO_Table->$tablename
	 * PMO_Object->$attribute=$value
	 * */
	public function getObjectByValue($tablename, $attribute, $value);

}


?>
