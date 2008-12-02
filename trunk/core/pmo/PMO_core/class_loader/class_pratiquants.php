<?php
require_once("class_passages.php");

class pratiquants extends PMO_MyObject{
	public static $TableName = 'pratiquants';
	
	public function IsFamille()
	{
		if($this->fk_famille == NULL || $this->fk_famille == $this->id)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	public function GetChefFamille()
	{
		if($this->IsFamille() && $this->fk_famille != NULL)
		{
			$prat = PMO_MyObject::factory(self::$TableName);
			$prat->id = $this->fk_famille;
			$prat->load();
			return $prat;
		}
		else
		{
			return NULL;
		}
	}

	public function GetSection()
	{
		if($this->fk_section != NULL && $this->fk_section != 0)
		{
			$section = PMO_MyObject::factory("sections");
			$section->id = $this->fk_section;
			$section->load();
			return $section;
		}
		else
		{
			return NULL;
		}
	}
	
	public function IsLicenceExpired()
	{
		if($this->licenceDate <= date("j/n/Y"))
			return true;
		else
			return false;
	}
	
	public function GetGrades()
	{
		return passages::GetByPratiquant($this->id);
	}
	
	public function GetGrade()
	{
		return passages::GetGradeByPratiquant($this->id);
	}
	
	public function IsReady($diff)
	{
					

	}
	
	/********************
	 *** Private Static *************************************
	 ********************/
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
	
	/*******************
	 *** Public Static *************************************
	 *******************/
	public static function GetAll()
	{
		$controler = new PMO_MyController();

		$map = $controler->queryController("SELECT * FROM " . self::$TableName . ";");
	
		return self::GetArray($map);
	}
	
	public static function GetBySection($fkSection)
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE fk_section = " . $fkSection . ";");
	
		return self::GetArray($map);
	}
	
	public static function GetChefs()
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE fk_famille ISNULL OR fk_famille = id;");
	
		return self::GetArray($map);
	}
	
	public static function GetByFirstName($name)
	{
		return GetByName($name, "");	
	}
	
	public static function GetByLastName($name)
	{
		return GetByName("", $name);
	}
	
	public static function GetByNameAndLastname($name)
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController('SELECT * FROM ' . self::$TableName . ' WHERE nom || " " || prenom like "' . $name . '%" OR prenom || " " || nom like "' . $name . '%" ;');
		
		return self::GetArray($map);
	}
	
	public static function GetByName($firstName, $lastName)
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController('SELECT * FROM ' . self::$TableName . ' WHERE nom like "' . $firstName . '%" AND prenom like "' . $lastName . '%" ;');
	
		return self::GetArray($map);
	}
	
	public static function GetExpired()
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController('SELECT * FROM ' . self::$TableName . ' WHERE licenceDate <= current_date;');
	
		return self::GetArray($map);		
	}
}

?>
