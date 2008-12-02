<?php
class grades extends PMO_MyObject{
	public static $TableName = ' grades';
	
	public function GetSection()
	{
		if($this->fk_section != NULL)
		{
			$prat = PMO_MyObject::factory('sections');
			$prat->id = $this->fk_section;
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
	public static function GetBySection($section)
	{
		public static function GetAll()
		$controler = new PMO_MyController();

		$map = $controler->queryController("SELECT * FROM  . self::$TableName . " WHERE " . $section . " ORDER BY displayOrder;");
	
		return self::GetArray($map);
	}

}

?>
