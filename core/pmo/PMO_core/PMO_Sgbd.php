<?php

 /**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 


/**
 * SGDB generical interface for all SGBD drivers
 */

interface PMO_Sgbd {
	
	/** 
	* Build PMO_Sgbd 
	* */
	static function factorySgbd();
	
	/**
	 * Create a data link with SGBD
	 */
	public function connectSgbd(array $authdb);
	
	/**
	 * Return DB link or DB object
	 */
	public function getDB();

	/**
	 * Set DB link or DB object
	 */
	public function setDB($object);
	
	/**
	 * Execute an SQL query
	 * Return true if it's ok
	 */
	public function querySgbd($query);

	/** Return results of query */
	public function fetchArray();

	/**
	 * query database 
	 * for a description of the $table schems 
	 * like a :
	 * DESC $table;
	 * DESCRIBE $table;
	 * SHOW $table;
	 */
	public function getTableDesc($table);
	
	/** 
	 * Retrieve the last insert
	 * primary key value
	 * */
	public function getLastId();
	
	/** insert an objet in database */
	public function insertObject(PMO_Object $object);
	
	/** update an objet in database */
	public function updateObject(PMO_Object $object);
	
	/** delete an objet in database */
	public function deleteObject(PMO_Object $object);
	
	/** load an object in database */
	public function loadObject(PMO_Object $object);
}
?>