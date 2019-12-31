<?php

 /**
 * @package PMO
 * @version 0.1
 * @copyright Copyright (C) 2007 Nicolas Boiteux nicolas_boiteux@yahoo.fr
 * @license GPL
 */ 


require_once('PMO_Parser.php');

class PMO_MyParser implements PMO_Parser{
	
	private $fields;
	private $tables;

	public function parseRequest($string){
		$string = addslashes($string);
		$string = preg_replace('/\s+/', ' ', $string);
		$str = explode(' ', $string);
		$str = str_replace(array(';', '\r', '\n'), array('', ' ', ' '), $str);

		$mode = '';
		foreach( $str as $word){
			switch($mode){
		        case 'SELECT':
					$this->fields = explode(',', $word);
					break;
		        case 'FROM':
		        	$this->tables = explode(',', $word);
		        	foreach($this->tables as $value)
		        		if(empty($value))
		        			throw new Exception("Fatal Error: Your SQL QUERY must only contains ',' between tables name. ");
		        					
		        	return TRUE;
			case 'WHERE':
					return TRUE;
			default:
					break;
			}
	
			$mode = '';
			
			$word = strtoupper($word);
			switch($word){
				case 'SELECT':
					$mode = $word;
					break;
				case 'FROM':
					$mode = $word;
					break;
				case 'WHERE':
					return TRUE;
				default:
					break;
			}
		}
	}

	public function getFields(){
		return $this->fields;
	}
	
	public function getTables(){
		return $this->tables;
	}

}
?>
