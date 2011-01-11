<?php
require_once("class_lnk_grades_sections.php");

	
class grades extends PMO_MyObject{
	public static $TableName = 'grades';
	
	// Récupère la liste des sections dans lesquelles ce pratiquant à des grades
	public function GetSections()
	{
		$sql = "select b.* ";
		$sql .= "from ";
		$sql .= self::$TableName . " as a inner join ";
		$sql .= lnk_grades_sections::$TableName . " as l on a.id = l.fk_grade inner join ";
		$sql .= sections::$TableName . " as b on l.fk_section = b.id ";
		$sql .= "where ";
		$sql .= "a.id = " . $this->id;
		$map = $controler->queryController($sql);
		
		return sections::GetArray($map);		
	}

	/*************
	 *** Static *************************************
	 *************/
	private static function  GetArray($map)
	{
		$i = 0;
		while ($result = $map->fetchMap())
		{
			$pratiquants[$i] = $result[self::$TableName];
			$i++;
		}
		return $pratiquants;
	}
	public static function GetAll()
	{
		$controler = new PMO_MyController();
 
		$map = $controler->queryController("SELECT * FROM " . self::$TableName . ";");
	
		return self::GetArray($map);
	}
	public static function GetBySection($section)
	{
		$controler = new PMO_MyController();
		$sql = "select a.* ";
		$sql .= "from " . self::$TableName . " as a, " . lnk_grades_sections::$TableName . " as l ";
		$sql .= "where a.id = l.fk_grade AND ";
		$sql .= "l.fk_section = " . $section;
		
		echo($sql);
		$map = $controler->queryController($sql);
	
		return self::GetArray($map);
	}

}

?>
