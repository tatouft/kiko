<?php

error_reporting (E_STRICT);

/**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 


	/* classe de demonstration 
	 *
	 */

require_once('core/PMO_MyController.php');

 class test {
	 	
	public function testOfPmo(){	
		
		$controller = new PMO_MyController();
		$time_start = microtime(true);
				
		$map = $controller->queryController("SELECT * FROM YOURTABLE;");
		
		$time_end = microtime(true);
		$time = $time_end - $time_start;
		echo "Did nothing in $time seconds\n";
		
		print '<pre>';print_r($user);print '</pre>';
	}
	
 }
 
$test = new test();
$test->testOfPmo();
 
?>