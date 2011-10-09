<?php
    
    class cotisationsPeriode extends PMO_MyObject{
        public static $TableName = 'cotisationsPeriode';
        
        
        public function GenerateCommunication($pratiquant, $periode)
        {
            $this->communication = generate_structured_communication("1" . str_pad($pratiquant->id , 5, "0", STR_PAD_LEFT) . str_pad($periode->id , 4, "0", STR_PAD_LEFT));
        }
        
        private function generate_structured_communication($transactionID) {
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
        
    }
    
    ?>