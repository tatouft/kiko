<?php
    require_once("class_periodes.php");
    
    class cotisationsPeriode extends PMO_MyObject{
        public static $TableName = 'cotisationsPeriode';
        
        
        public function GenerateCommunication($pratiquantId, $periodeId)
        {
            $this->communication = self::GetCommunication($pratiquantId, $periodeId);
        }
        
        public function GetPeriode()
        {
            return periodes::GetById($this->fk_periode);
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
        
        public static function GetPaiedByPratiquantForSeason($prat)
        {
            $controler = new PMO_MyController();

            $today = getdate();
            $year = $today[year];
            if($today[mon] < 9)
            {
                $year = $year - 1;
            }

            
            $sql = "SELECT cp.*";
            $sql .= " FROM " . self::$TableName . " as cp";
            $sql .= "   , " . periodes::$TableName . " as p";
            $sql .= " WHERE cp.fk_pratiquant = " . $prat->id;
            $sql .= "   AND cp.fk_periode = p.id";
            //$sql .= "   AND cp.enOrdre = 1";
            $sql .= "   AND p.dateDebut >= '" . $year . "-09-01'";
            $sql .= " ORDER BY p.dateDebut ASC";
            
            $map = $controler->queryController($sql);
            
            $table = self::GetArray($map);
            return $table;
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
        
        public static function GetCommunication($pratiquantId, $periodeId)
        {
            $transactionId = "1" . str_pad($pratiquantId , 4, "0", STR_PAD_LEFT) . str_pad($periodeId , 5, "0", STR_PAD_LEFT);
            return self::generate_structured_communication($transactionId);
        }
        
        private static function generate_structured_communication($transactionID) 
        {
            if(strlen($transactionId) > 10)
            {
                $transactionId = substr($transactionId, 0, 10);
            }
            
            $control = bcmod($transactionID, 97);
            
            $control = ($control == "0") ? "97" : $control;
            
            if ($control < 10) {
                $control = "0" . $control;
            }
            
            $transactionID = str_pad($transactionID, 10, "0", STR_PAD_LEFT);
            $com = $transactionID . $control;
            
            return substr($com, 0, 3) . "/" . substr($com, 3, 4) . "/" . substr($com, 7, 5);
        }
    }
    
    ?>