<?php
setlocale(LC_TIME, "fr_FR");
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ERROR);
if(!class_exists('TCalendar'))
{
	define('TCalendar_VERSION','0.93');

	class TCalendar
	{
		/***************************
		/* Variables
		/**************************/
		var $Tmonth;
		
		/***************************
		/* Constructeurs
		/**************************/
		function TCalendar()
		{
		}
		
		/***************************
		/* Acesseurs
		/**************************/		
		function GetMonth()
		{
			$month = getdate($this->Tmonth);
			return $month["mon"];
		}
		
		function GetYear()
		{
			$month = getdate($this->Tmonth);
			return $month["year"];
		}
		
		function SetDate($Tmonth)
		{
			$month = getdate($Tmonth);
			
			// retour au 1er du mois
			$Tmonth -= ($month["mday"] - 1) * 24 * 60 * 60;

			$this->Tmonth = $Tmonth;
		}

		/***************************
		/* Misc
		/**************************/	
		function PreviousMonth()
		{
			// retour au dernier jour du mois precedent
            $this->Tmonth = strtotime("-1 months",$this->Tmonth);
			//$this->Tmonth -= 24 * 60 * 60;

			$month = getdate($this->Tmonth);
			
			// retour au 1er du mois
			$this->Tmonth -= ($month["mday"] - 1) * 24 * 60 * 60;
		}
		
		function NextMonth()
		{
			// retour au 1er du mois suivant
            $this->Tmonth = strtotime("+1 months",$this->Tmonth);
			//$this->Tmonth += (date("t",$this->Tmonth)) * 24 * 60 * 60;
		}
		
		/***************************
		/*Renders
		/**************************/	
		function WriteCalendar() 
		{
			$this->WriteCalendarByDate($this->Tmonth);
		}
		
		function WriteCalendarByDate($Tmonth) // Tmonth est un timestamp
		{
			$month = getdate($Tmonth);
			
			// retour au 1er du mois
			$this->Tmonth = $Tmonth;
			$Tmonth -= ($month["mday"] - 1) * 24 * 60 * 60;
			
			echo "<div class='DocHead'>" . strftime('%B',$this->Tmonth) . " " . date('Y',$this->Tmonth) . "</div>";
			echo("<div class='conteneur'>");
			echo $this->GetHeader();
			echo $this->RenderMonth($Tmonth);
			echo("<div class='end'></div>");		
			echo("</div>");
		}
		
		function GetHeader()
		{
			$head .= "<div class='head'>Lundi</div>";
			$head .= "<div class='head'>Mardi</div>";
			$head .= "<div class='head'>Mercredi</div>";
			$head .= "<div class='head'>Jeudi</div>";
			$head .= "<div class='head'>Vendredi</div>";
			$head .= "<div class='head'>Samedi</div>";
			$head .= "<div class='head'>Dimanche</div>";
			return $head;
		}
		
		function RenderMonth($Tmonth)
		{
			$month = getdate($Tmonth);
			
			// Trouver le nombre de jour du mois courent
			$limit = date("t",$month[0]);
			$cal = "";
			
			// met des cases vides pour commencer le bon jour de la semaine
			$x = $month["wday"]-1;
			if($x == -1) $x = 6;
			$cal .= $this->GetDateEmptyBox($x);
            
			// genere tous les jours du mois
			for($i = 0; $i<$limit; $i++)
			{
                $isToday = false;
                $today = date_create();
                $current = date_create();
                $current->setDate($this->GetYear(), $this->GetMonth(), $i+1);
                if($today->format("Y") == $current->format("Y") 
                   && $today->format("m") == $current->format("m")
                   && $today->format("j") == $current->format("j"))
                    $isToday = true;

                
				$cal .= $this->GetDateBox($Tmonth, $isToday);
                $Tmonth = strtotime("+1 days",$Tmonth);
			}
			
            $debordement = (($limit + $x) % 7);
            if($debordement != 0)
                $reste = 7 - $debordement;
            else
                $reste = 0;
			$cal .= $this->GetDateEmptyBox($reste); 
			return $cal;
		}
		function GetDateEmptyBox($x)
		{
			$empty = "";
			$begin = $x;
			for(; $x > 0; $x--)
			{
				if($begin == $x && $x == 1)
					$empty .= "<div class='edayBE'>&nbsp;</div>";
				else if($begin == $x)
					$empty .= "<div class='edayB'>&nbsp;</div>";
				else if($x == 1)
					$empty .= "<div class='edayE'>&nbsp;</div>";
				else
					$empty .= "<div class='eday'>&nbsp;</div>";
			}
			return $empty;
		}
		function GetDateBox($Tdate, $today)
		{
            $stoday="";
            if($today)
                $stoday = "today";
			$date = getdate($Tdate);
			$weekend;
			if($date["wday"] == 0 || $date["wday"] == 6)
				$weekend = "weekend";
			$day = "<div class='day ". $weekend . " " . $stoday ."'>". $date["mday"] ."</div>";
			return $day;
		}
	}
}
?>