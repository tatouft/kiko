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
        public static function GetAllCurent()
        {
            $controler = new PMO_MyController();
            
            $year = intval(date("Y"));
            $year = $year - 1;
            $map = $controler->queryController("SELECT * FROM " . self::$TableName . " WHERE dateFin > '" . $year . "-01-01' ORDER BY dateDebut ASC, dateFin DESC;");
            
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
            
            $year = intval(date("Y"));
            $year = $year - 1;

            $sql =  "SELECT p.* FROM";
            $sql .= " " . self::$TableName . " as p";
            $sql .= " WHERE id NOT IN (";
            $sql .= " SELECT fk_periode FROM " . cotisationsPeriode::$TableName . " as c";
            $sql .= " WHERE fk_pratiquant = " . $id;
            $sql .= ")";
            $sql .= " AND dateFin > '" . $year . "-01-01'";
            $sql .= "  ORDER BY dateDebut ASC";
            
            $map = $controler->queryController($sql);
            
            return periodes::GetArray($map);
        }

        public static function GetToPayByPratiquant($prat)
        {
            $controler = new PMO_MyController();
            
            $sql =  "SELECT p.* FROM ";
            $sql .=  self::$TableName . " as p,";
            $sql .=  presences::$TableName  . " as pr ";
            $sql .= "WHERE ";
            $sql .=   "p.dateDebut < pr.date AND p.dateFin >= pr.date ";
            $sql .=   "AND NOT EXISTS (select * FROM cotisationsPeriode WHERE fk_periode == p.id AND fk_pratiquant == pr.fk_pratiquant) ";
            $sql .=   "AND NOT EXISTS (select * FROM cotisationsPeriode cp, periodes as pp ";
            $sql .=                            "WHERE pp.id == cp.fk_periode AND cp.fk_pratiquant == pr.fk_pratiquant ";
            $sql .=                              "AND pp.dateDebut < pr.date AND pp.dateFin >= pr.date) ";
            $sql .=   "AND (p.dateFin - p.dateDebut) == (SELECT MIN(dateFin - dateDebut) FROM periodes WHERE dateDebut < pr.date AND dateFin >= pr.date) ";
            $sql .=   "AND pr.fk_pratiquant == " . $prat->id . " ";
            $sql .= "GROUP BY p.id ";
            $sql .= "ORDER BY p.dateDebut ASC";

            //print($sql);
            
            $map = $controler->queryController($sql);
            
            $table = self::GetArray($map);
            return $table;
        }
    }
    
    ?>
