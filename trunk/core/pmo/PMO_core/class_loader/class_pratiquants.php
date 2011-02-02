<?php
require_once("class_passages.php");
require_once("class_presences.php");

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
	
	public function AddPresence($date)
	{
		if(!presences::Exists($this->id, $date))
		{
			$presence = PMO_MyObject::factory("presences");
			
			$presence->date = $date;
			$presence->fk_pratiquant = $this->id;
			$presence->commit();
		}
	}
	
	public function AddPresences($n)
	{
		for($i = 0; $i<$n; $i++)
		{
			$presence = PMO_MyObject::factory("presences");
			
			$date = date("Y-m-d");
			$presence->date = $date;
			$presence->fk_pratiquant = $this->id;
			$presence->commit();
		}
	}
	
	public function IsLicenceExpired()
	{
		if($this->licenceDate <= date("Y-m-d"))
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
	
	public function GetPresences()
	{
		return presences::GetByPratiquant($this->id);
	}
	public function GetPresencesCountFromLastGrade()
	{
		return count(presences::GetByPratiquantFromLastGrade($this->id, $this->GetGrade()->date));
	}
	public function GetRestToNextGrade()
	{
		if($this->GetGrade() == null)
			return 0;
		
		$need = $this->GetGrade()->GetGrade()->jours;
		//echo($need . " - " . $this->GetPresencesCountFromLastGrade() . " = ");
		return $this->GetPresencesCountFromLastGrade() - $need;
	}
	
	public function IsReady()
	{
		if($this->GetGrade() == null)
			return false;
			
		$presences = $this->GetPresencesCountFromLastGrade();
		$need = $this->GetGrade()->GetGrade()->jours;
		//echo($presences);
		if($presences >= $need - 4)
			return true;
		else
			return false;

	}
	
	public function ExistsPhoto()
	{
		$fullPath = $this->GetPhotoSystemPath();
		if($this->photo != null && file_exists($fullPath))
		{
			return true;
		}
		return false;
	}
	
	public function GetPhotoTitle()
	{
		if($this->ExistsPhoto())
		{
			return "";
		}
		return "No photo at: " . $this->GetPhotoSystemPath();
	}
	
	public function GetPhotoHttpPath()
	{
		if($this->ExistsPhoto())
		{
			return "photos" . "/" . $this->photo;
		}
		return "css/images/NoPhoto.png";
	}
	
	public function GetPhotoSystemPath()
	{
		return $_SERVER['DOCUMENT_ROOT'] . "photos" . "/" . $this->photo;
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
	public static function GetById($id)
	{
		$prat = PMO_MyObject::factory(self::$TableName);
		$prat->id = $id;
		$prat->load();		
		return $prat;
	}
	
	public static function GetAll()
	{
		$controler = new PMO_MyController();

		$map = $controler->queryController("SELECT * FROM " . self::$TableName . " ORDER BY nom ASC;");
	
		return self::GetArray($map);
	}
	
	public static function GetBySection($fkSection)
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE fk_section = " . $fkSection . ";");
	
		return self::GetArray($map);
	}
	
	public static function GetBySections($fkSections)
	{
		//echo("SELECT * FROM " . self::$TableName . " WHERE fk_section in (" . $fkSections . ");");
		$controler = new PMO_MyController();
		$map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE fk_section in (" . $fkSections . ") ORDER BY nom ASC;");
		
		return self::GetArray($map);
	}
	
	public static function GetByExam($fkSection, $date)
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE fk_section = " . $fkSection . " AND ;");
		
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
