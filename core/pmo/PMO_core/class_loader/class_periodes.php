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
        public static function GetNewForPratiquant($id)
        {
            $controler = new PMO_MyController();
            
            $sql =  "SELECT p.* FROM";
            $sql .= " " . self::$TableName . " as p";
            $sql .= " WHERE id NOT IN (";
            $sql .= " SELECT fk_periode FROM " . cotisationsPeriode::$TableName . " as c";
            $sql .= " WHERE fk_pratiquant = " . $id;
            $sql .= ")";
            $sql .= "  ORDER BY dateDebut ASC";
            
            $map = $controler->queryController($sql);
            
            return periodes::GetArray($map);
            
        }
    }
    
    ?>