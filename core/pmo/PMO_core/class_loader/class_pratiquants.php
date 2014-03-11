<?php
require_once("class_passages.php");
require_once("class_presences.php");
require_once("class_cotisationsSimple.php");
require_once("class_cotisationsPeriode.php");
    
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
    public function GetPresencesNeededForNextGrade()
    {
        return $this->GetGrade()->GetGrade()->GetNextGrade($this->fk_section)->jours;
    }
	public function GetRestToNextGrade()
	{
		if($this->GetGrade() == null)
		{
			return 0;
		}
		
		$need = $this->GetPresencesNeededForNextGrade();
		//echo($need . " - " . $this->GetPresencesCountFromLastGrade() . " = ");
		return $this->GetPresencesCountFromLastGrade() - $need;
	}
    
    // Recupere le nombre de stage auquel le pratiquant a participe cette annee
    public function GetCountStages()
    {
        return 0;
    }
    
    // recupere le nombre de periode que le pratiquant n'a pas paye
    public function GetCountNoPayPeriod()
    {
        return count(cotisationsPeriode::GetToPayByPratiquant($this));
    }
    public function GetNoPayPeriod()
    {
        return cotisationsPeriode::GetToPayByPratiquant($this);
    }
    public function GetPaiedPeriodForSeason()
    {
        return cotisationsPeriode::GetPaiedByPratiquantForSeason($this);
    }
    
    // recupere le nombre de cours que le pratiquant n'a pas paye
    public function GetCountNoPayLesson()
    {
        return count(cotisationsSimple::GetToPayByPratiquant($this));
    }
    public function GetNoPayLesson()
    {
        return cotisationsSimple::GetToPayByPratiquant($this);
    }
    
    public function GetPresencesCountForThisSeason()
    {
		return count(presences::GetByPratiquantForThisSeason($this->id));        
    }
    
	public function IsReady()
	{
		if($this->GetGrade() == null)
		{
			return false;
		}
			
		$presences = $this->GetPresencesCountFromLastGrade();
		$need = $this->GetPresencesNeededForNextGrade();
		//echo($presences);
		if($presences >= $need - 4)
		{
			return true;
		}
		else
		{
			return false;
		}

	}
    
    public function GetFamille()
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE deleted = 0 AND fk_famille = " . $this->id . ";");
        
		return self::GetArray($map);
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
		return $_SESSION['SiteRoot'] . "/photos" . "/" . $this->photo;
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

		$map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE deleted = 0 ORDER BY nom, prenom ASC;");
	
		return self::GetArray($map);
	}
	
	public static function GetBySection($fkSection)
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE fk_section = " . $fkSection . " AND  deleted = 0 ORDER BY nom, prenom ASC;");
	
		return self::GetArray($map);
	}
	
	public static function GetBySections($fkSections)
	{
		//echo("SELECT * FROM " . self::$TableName . " WHERE fk_section in (" . $fkSections . ");");
		$controler = new PMO_MyController();
		$map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE fk_section in (" . $fkSections . ") AND deleted = 0 ORDER BY nom ASC;");
		
		return self::GetArray($map);
	}
	
	public static function GetByExam($fkSection)
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE fk_section = " . $fkSection . " AND ;");
		/*SELECT p.id, count(pr.fk_pratiquant) as nbPresences
		FROM 
		pratiquants as p INNER JOIN
		presences as pr ON pr.fk_pratiquant = p.id INNER JOIN
		passages as pa ON pa.fk_pratiquant = p.id AND pa.date = (SELECT p2.date FROM passages as p2 WHERE p2.fk_pratiquant = p.id ORDER BY p2.date DESC) INNER JOIN
		grades as g on g.id = pa.fk_grade
		WHERE 
		p.fk_section = 1 AND
		pr.date > pa.date AND
		count(pr.fk_pratiquant) > g.jours - 4
		GROUP BY p.id*/
		return self::GetArray($map);
	}
	
	public static function GetChefs()
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE deleted = 0 AND (fk_famille ISNULL OR fk_famille = id);");
	
		return self::GetArray($map);
	}
	public static function GetPoubelle()
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE deleted = 1;");
	
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
