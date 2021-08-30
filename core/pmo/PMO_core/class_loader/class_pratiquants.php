<?php
require_once("class_passages.php");
require_once("class_presences.php");
require_once("class_cotisationsSimple.php");
require_once("class_cotisationsPeriode.php");
    
class pratiquants extends PMO_MyObject{
	public static $TableName = 'pratiquants';
	
	public function IsFamilyMember()
	{
		if($this->fk_famille == NULL || $this->fk_famille == '' || $this->fk_famille == $this->id)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	public function GetFamilyHead()
	{
		if($this->IsFamilyMember())
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

	public function HasLicence()
	{
		return $this->licenceNbr != "" ;
	}
	public function IsLicenceExpired()
	{
		if($this->licenceNbr != "" && $this->licenceDate <= date("Y-m-d"))
			return true;
		else
			return false;
	}
	public function IsLicenceExpiredInNextMonth()
	{
		$exp = strtotime($this->licenceDate);
		$willExp = strtotime('-1 month', $exp);

		if($this->licenceNbr != "" && $willExp <= strtotime("now"))
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
    	if($this->GetGrade())
		{
			return $this->GetGrade()->GetGrade()->GetNextGrade($this->fk_section)->jours;
		}
    	else
		{
			return  0;
		}
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
        return count(periodes::GetToPayByPratiquant($this));
    }
    public function GetNoPayPeriod()
    {
        return periodes::GetToPayByPratiquant($this);
    }
    public function GetNoPayLessonInPeriod($period)
    {	
		return count(presences::GetByPratiquantForThisPeriod($this->id, $period->id));
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
        //return cotisationsSimple::GetToPayByPratiquant($this);

		$controler = new PMO_MyController();

		$sql = "select pr.* ";
		$sql .= "from " . presences::$TableName . " as pr ";
		$sql .= "where pr.fk_pratiquant = " . $this->id;
		$sql .= "  AND not exists ( ";
		$sql .= "           select 1 ";
		$sql .= "           FROM cotisationsSimple cs ";
		$sql .= "           WHERE cs.fk_presences = " . $this->id;
		$sql .= "           )";
		$sql .= "  AND not exists (";
		$sql .= "           select 1 ";
		$sql .= "           from " . periodes::$TableName . " as pe, " . cotisationsPeriode::$TableName . " as co ";
		$sql .= "           where pe.id = co.fk_periode ";
		$sql .= "             AND co.fk_pratiquant = " . $this->id;
		$sql .= "             AND pr.date >= pe.dateDebut ";
		$sql .= "             AND pr.date <= pe.dateFin ) ";

		$map = $controler->queryController($sql);

		$i = 0;
		while ($result = $map->fetchMap())
		{
			$presences[$i] = $result[presences::$TableName];
			$i++;
		}
		return $presences;
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
    
    public function GetFamily()
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE deleted = 0 AND fk_famille = " . $this->id . " AND id != " . $this->id .";");

		return self::GetArray($map);
	}

	public function GetFamilyNameList()
	{
		$familyMembers = $this->GetFamily();
		if(count($familyMembers) == 0)
		{
			return "";
		}

		$memberNames = "";
		foreach($familyMembers as $familyMember)
		{
			$memberNames .= $familyMember->nom . " " . $familyMember->prenom . ", ";
		}

		return substr($memberNames, 0, -2); // remove last ,
	}
	
	public function IsHttpPhoto()
	{
		return preg_match('/^http(s)?:/', $this->photo);
	}
	
	public function ExistsPhoto()
	{
		if($this->IsHttpPhoto())
		{
			return true;
		}
		else
		{
			$fullPath = $this->GetPhotoSystemPath();
			if($this->photo != null && 1) //file_exists($fullPath))
			{
				return true;
			}
			return false;
		}
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
			if ($this->IsHttpPhoto())
				return $this->photo;
			else
				return "photos" . "/DropboxThumb2.php?path=" . $this->photo;
		}
		return "css/images/NoPhoto.png";
	}
	
	public function GetPhotoSystemPath()
	{
		return $_SESSION['SiteRoot'] . "/photos" . "/" . $this->photo;
	}

	public function HasMoreThan14()
	{
		$nais = strtotime($this->naissance);
		$fourteen = strtotime('+14 year', $nais);
		return ($fourteen <= strtotime("now"));
	}

	public function AllowPub()
	{
		return $this->pub == 1;
	}
	public function SetAllowPub()
	{
		$this->pub = 1;
	}
	public function DisallowPub()
	{
		return $this->pub == -1;
	}
	public function SetDisallowPub()
	{
		$this->pub = -1;
	}
	public function UnknownPub()
	{
		return $this->pub == 0;
	}
	public function SetUnknownPub()
	{
		$this->pub = 0;
	}
	/********************
	 *** Private Static *************************************
	 ********************/
	private static function  GetArray($map)
	{
		$i = 0;
		$pratiquants = array();
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
		$map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE deleted = 0 AND (fk_famille ISNULL OR fk_famille = '' OR fk_famille = id);");
	
		return self::GetArray($map);
	}

	public static function GetChefsButMe($myId)
	{
		$controler = new PMO_MyController();

		$query = "SELECT 
    					*
					FROM " . self::$TableName . "
					WHERE
						(fk_famille ISNULL OR fk_famille = '' OR fk_famille = id)
					    AND id != " . $myId . "
						AND deleted = 0
					ORDER BY nom
				";

		$map = $controler->queryController($query);

		return self::GetArray($map);
	}

	public static function GetPoubelle()
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE deleted = 1;");
	
		return self::GetArray($map);
	}

	public static function GetByLastNameButMe($lastName, $myId)
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController('SELECT * FROM ' . self::$TableName . ' WHERE deleted = 0 AND nom like "' . $lastName . '%" AND id != ' . $myId . ';');

		return self::GetArray($map);
	}

	public static function GetByName($firstName, $lastName)
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController('SELECT * FROM ' . self::$TableName . ' WHERE prenom like "' . $firstName . '%" AND nom like "' . $lastName . '%" ;');

		return self::GetArray($map);
	}

	public static function GetByFirstName($name)
	{
		return pratiquants::GetByName($name, "");
	}
	
	public static function GetByLastName($name)
	{
		return pratiquants::GetByName("", $name);
	}
	
	public static function GetByNameAndLastname($name)
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController('SELECT * FROM ' . self::$TableName . ' WHERE nom || " " || prenom like "' . $name . '%" OR prenom || " " || nom like "' . $name . '%" ;');
		
		return self::GetArray($map);
	}
	
	public static function GetExpired()
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController('SELECT * FROM ' . self::$TableName . ' WHERE licenceDate <= current_date AND  deleted = 0;');
	
		return self::GetArray($map);		
	}
	
	public static function GetCount($date1, $date2)
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController('select * from ' . self::$TableName . ' as pra where licenceNbr is not "" AND deleted != 1 AND pra.id in (select pre.fk_pratiquant from ' . presences::$TableName . ' as pre where pre.date > "' . $date1 . '" and pre.date < "' . $date2 . '")');
	
		return count(self::GetArray($map));		
	}
	
	public static function GetCountNeupre($date1, $date2)
	{
		$controler = new PMO_MyController();
		$map = $controler->queryController('select * from ' . self::$TableName . ' as pra where licenceNbr is not "" AND deleted != 1 AND pra.codePostal in ("4120", "4121", "4122") and pra.id in (select pre.fk_pratiquant from ' . presences::$TableName . ' as pre where pre.date > "' . $date1 . '" and pre.date < "' . $date2 . '")');
	
		return count(self::GetArray($map));		
	}
	
	public static function GetCountAge($date1, $date2, $age1, $age2, $male)
	{
		$today = getdate();
		$year = $today["year"];
		$dateAge1 = ($year - $age1) . "-12-31";
		$dateAge2 = ($year - $age2) . "-01-01";
		
		$controler = new PMO_MyController();
		$map = $controler->queryController('select nom, prenom, sexe from ' . self::$TableName . ' as pra where licenceNbr is not "" AND deleted != 1 AND pra.id in (select pre.fk_pratiquant from presences as pre where pre.date > "' . $date1 . '" and pre.date < "' . $date2 . '") and pra.sexe = ' . $male . ' and pra.naissance >= "' . $dateAge2 . '" and pra.naissance <= "' . $dateAge1 . '" ');
	
		return count(self::GetArray($map));		
	}
	public static function GetCountAgeNeupre($date1, $date2, $age1, $age2)
	{
		$today = getdate();
		$year = $today["year"];
		$dateAge1 = ($year - $age1) . "-12-31";
		$dateAge2 = ($year - $age2) . "-01-01";

		$controler = new PMO_MyController();
		$map = $controler->queryController('select nom, prenom, sexe from ' . self::$TableName . ' as pra where licenceNbr is not "" AND deleted != 1 AND pra.codePostal in ("4120", "4121", "4122") AND pra.id in (select pre.fk_pratiquant from presences as pre where pre.date > "' . $date1 . '" and pre.date < "' . $date2 . '") and pra.naissance >= "' . $dateAge2 . '" and pra.naissance <= "' . $dateAge1 . '" ');

		return count(self::GetArray($map));
	}
    public static function GetPresencesBetweenTwoDates($date1, $date2)
    {
		$sql = "SELECT DISTINCT pra.*
				FROM
					" . self::$TableName . " as pra INNER JOIN
					presences as pre
				WHERE
					pra.id = pre.fk_pratiquant AND
					pre.date >= '" . $date1 . "' AND
					pre.date <= '" . $date2 . "'
				ORDER BY
					pra.nom";

		$controler = new PMO_MyController();
		$map = $controler->queryController($sql);

		return self::GetArray($map);
    }
}

?>
