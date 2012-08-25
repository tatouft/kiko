<?php

/**
 * Setup your database configuration
 *  mysql / pgsql
 */

    /** 
	* Choose between
	* sqlite / mysql / pgsql / pdo 
	* */
	$driverz = "sqlite";
	
	/** if you use sqlite */
	$dsn = 'sqlite:' . $_SESSION['SiteRoot'] .'/db/kome';	
	
	/** if you use other sgbd */
	$hostz = 'localhost';
	$userz = 'pmo';
	$passz = 'pmo';
	$basez = 'sakila';
	
	
	//require_once("core/pmo/PMO_core/class_loader/class_pratiquants.php");

?>
