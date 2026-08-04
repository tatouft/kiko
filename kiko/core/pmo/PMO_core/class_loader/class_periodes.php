<?php
    	
    class periodes extends PMO_MyObject{
        public static $TableName = 'periodes';
        
        
        /*************
         *** Static *************************************
         *************/
        public static function  GetArray($map)
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

            $sql =  "SELECT p.* FROM
                     " . self::$TableName . " as p
                     WHERE 
                       id NOT IN (
                         SELECT fk_periode FROM " . cotisationsPeriode::$TableName . " as c 
                           WHERE fk_pratiquant = " . $id . "
                       )
                       AND p.dateFin > '" . $year . "-01-01'
                       AND p.dateFin > (SELECT ps.date FROM passages as ps WHERE ps.fk_pratiquant = " . $id . " ORDER BY ps.date ASC LIMIT 1)
                       AND 0 = (SELECT count(c2.fk_periode) FROM cotisationsPeriode as c2, periodes as p2
                                WHERE c2.fk_periode = p2.id
                                  AND fk_pratiquant = " . $id . "
                                  AND p2.dateDebut <= p.dateDebut
                                  AND p2.dateFin >= p.dateFin)
                       ORDER BY dateDebut ASC";
            
            $map = $controler->queryController($sql);
            
            return periodes::GetArray($map);
        }

        public static function GetToPayByPratiquant($prat)
        {
            $controler = new PMO_MyController();
            
            $sql =  "SELECT p.* FROM 
                       " . self::$TableName . " as p,
                       " . presences::$TableName  . " as pr
                       WHERE 
                         p.dateDebut < pr.date AND p.dateFin >= pr.date
                         AND NOT EXISTS (select * FROM cotisationsPeriode WHERE fk_periode == p.id AND fk_pratiquant == pr.fk_pratiquant)
                         AND NOT EXISTS (select * FROM cotisationsPeriode cp, periodes as pp
                                                  WHERE pp.id == cp.fk_periode AND cp.fk_pratiquant == pr.fk_pratiquant
                                                    AND pp.dateDebut < pr.date AND pp.dateFin >= pr.date)
                         AND (p.dateFin - p.dateDebut) == (SELECT MIN(dateFin - dateDebut) FROM periodes WHERE dateDebut < pr.date AND dateFin >= pr.date)
                         AND pr.fk_pratiquant == " . $prat->id . "
                       GROUP BY p.id
                       ORDER BY p.dateDebut ASC";

            //print($sql);
            
            $map = $controler->queryController($sql);
            
            $table = self::GetArray($map);
            return $table;
        }
    }
    
    ?>
