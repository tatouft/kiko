<?php
    	
    class periodes extends PMO_MyObject{
        public static $TableName = 'periodes';
        
        
        /*************
         *** Static *************************************
         *************/
        public static function  GetArray($map)
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
        public static function GetById($id)
        {
            $prat = PMO_MyObject::factory(self::$TableName);
            $prat->id = $id;
            $prat->load();		
            return $prat;
        }

    }
    
    ?>