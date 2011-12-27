<?php
	
	class presences extends PMO_MyObject{
		public static $TableName = 'presences';

		
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
		
		public static function Exists($pid, $date)
		{
			$controler = new PMO_MyController();
			
			$map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE fk_pratiquant = " . $pid . " AND date = '" . $date . "';");
			
			return count(self::GetArray($map)) > 0;
		}
		
		public static function DeleteItem($pid, $date)
		{
			$controler = new PMO_MyController();
			
			$controler->queryController("DELETE FROM " . self::$TableName . " WHERE fk_pratiquant = " . $pid . " AND date = '" . $date . "';");
		}
		
		public static function GetByPratiquant($pid)
		{
			$controler = new PMO_MyController();
			
			$map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE fk_pratiquant = " . $pid . ";");
			
			return self::GetArray($map);
		}
		public static function GetByPratiquantFromLastGrade($pid, $date)
		{
			$controler = new PMO_MyController();
			
			$map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE fk_pratiquant = " . $pid . " AND date > '" . $date . "';");
			
			return self::GetArray($map);
		}
        public static function GetByPratiquantForThisSeason($pid)
        {
			$controler = new PMO_MyController();
            $today = getdate();
            $year = $today[year];
            if($today[mon] < 9)
            {
                $year = $year - 1;
            }
			
			$map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE fk_pratiquant = " . $pid . " AND date > '" . $year . "-09-01';");
			
			return self::GetArray($map);            
        }
	}	
?>