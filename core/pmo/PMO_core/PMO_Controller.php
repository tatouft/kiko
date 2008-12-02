<?php

/**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 


/**
 * Controller to get map and commit objects
 */
 
interface PMO_Controller {

	/** 
	 * return a map PMO_Map that contains PMO_Object
	 *  from sql request 
	 * */
	public function queryController($query);

}
?>
