<?php
class sections extends PMO_MyObject{
	public static $TableName = 'sections';
	

	/*************
	 *** Static *************************************
	 *************/
	private static function  GetArray($map)
	{
		$pratiquants = array();
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
}

?>
