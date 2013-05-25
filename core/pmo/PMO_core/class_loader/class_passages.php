<?php
require_once("class_grades.php");
	
class passages extends PMO_MyObject{
	public static $TableName = 'passages';
		
	/* GetGrade */
	public function GetGrade()
	{
		if($this->fk_grade != NULL)
		{
			$prat = PMO_MyObject::factory("grades");
			$prat->id = $this->fk_grade;
			$prat->load();
			return $prat;
		}
		else
		{
			return NULL;
		}
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
	
	/* Get all passages for a pratiquant */
	public static function GetByPratiquant($fkPratiquant)
	{
		$controler = new PMO_MyController();
        $request = "SELECT a.* FROM " . self::$TableName . " as a, " . grades::$TableName  . " as b WHERE a.fk_pratiquant = " . $fkPratiquant . " AND a.fk_grade = b.id ORDER BY b.displayorder;";
        $map = $controler->queryController($request);
	
		return self::GetArray($map);
	}
	
	/* Get Grade of the pratiquant */
	public static function GetGradeByPratiquant($fkPratiquant)
	{
        if($fkPratiquant == 0 || $fkPratiquant == '')
            return NULL;
		$controler = new PMO_MyController();
		$query = "SELECT * FROM " . self::$TableName . " WHERE fk_pratiquant = " . $fkPratiquant . " ORDER BY date DESC;";
		$map = $controler->queryController($query);
		$grades = self::GetArray($map);
		
        if(count($grades) > 0)
			return $grades[0];
		else
			return NULL;
	}
}

?>
