<?php
    
    class cotisationsPeriode extends PMO_MyObject{
        public static $TableName = 'cotisationsSimple';
        
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
        public static function GetByPratiquant($prat)
        {
            $controler = new PMO_MyController();
            
            $sql = "select * ";
            $sql .= "from " . self::$TableName;
            $sql .= "where fk_pratiquant = " . $prat->id;
            
            $map = $controler->queryController($sql);
            
            return self::GetArray($map);
        }
        public static function GetToPayByPratiquant($prat)
        {
            $controler = new PMO_MyController();
            
            $sql = "select pr.fk_pratiquant, pr.id, 5 ";
            $sql .= "from " . presences::$TableName . " as pr ";
            $sql .= "where pr.fk_pratiquant = " $prat->id;
            $sql .= "  AND not exists (select * ";
            $sql .= "           from " . periodes::$TableName . " as pe, " . cotisationsPeriode . " as co ";
            $sql .= "           where pe.id = co.fk_periode ";
            $sql .= "             AND co.fk_pratiquant = pr.fk_pratiquant";
            $sql .= "             AND pr.date >= pe.dateDebut ";
            $sql .= "             AND pr.date <= pe.dateFin )";
            
            $map = $controler->queryController($sql);
            
            return self::GetArray($map);
        }
        
    }
    
    ?>