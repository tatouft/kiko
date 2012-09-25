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
    
$month = $_REQUEST['month'];
$year = $_REQUEST['year'];
$download = $_REQUEST['download'];
$pdf = $_REQUEST['pdf'];
    
    
$cal = new TCalendar;

if($month > 0 && $year > 0)
{
	$time = mktime(0, 0, 0, $month, 1, $year);
	$cal->SetDate($time);
}
else
{
	$cal->SetDate(time());
}
$PCal = clone $cal;
$PCal->PreviousMonth();

$NCal = clone $cal;
$NCal->NextMonth();
    
ob_start();
?>

    <style type="text/css">
        <?
        $BoxWidth = 142;
        $FullWidth = 7 * $BoxWidth;

        $BoxHeight = 112;
        ?>
        .conteneur
        {
            width: <? echo $FullWidth ?>px;
            border: 1px solid #3F7D91;
        }
        .DocHead
        {
            width: <? echo $FullWidth ?>px;
            text-align: center;
            font-weight: bold;
        }
        .head
        {
            width: <? echo ($BoxWidth - 2) ?>px;
            height: 30px;
            border: 1px solid #3F7D91;
            border-bottom: 2px solid #3F7D91;
            float: left;
            text-align: center;
            background-color: #E7EEEC;
            color: #3F7D91;
            font-weight: bold;
            padding-top: 5px;
            padding-bottom: 0px;
            overflow: hidden;
        }
        .edayB
        {
            width: <? echo ($BoxWidth - 1) ?>px;
            height: <? echo ($BoxHeight - 2) ?>px;
            border: 0px solid white;
            border-left: 1px solid #3F7D91;
            border-top: 1px solid #3F7D91;
            border-bottom: 1px solid #3F7D91;
            float: left;
        }
        .edayE
        {
            width: <? echo ($BoxWidth - 1) ?>px;
            height: <? echo ($BoxHeight - 2) ?>px;
            border: 0px solid white;
            border-right: 1px solid #3F7D91;
            border-top: 1px solid #3F7D91;
            border-bottom: 1px solid #3F7D91;
            float: left;
        }			
        .edayBE
        {
            width: <? echo ($BoxWidth - 2) ?>px;
            height: <? echo ($BoxHeight - 2) ?>px;
            border: 1px solid #3F7D91;
            float: left;
        }
        .eday
        {
            width: <? echo $BoxWidth ?>px;
            height: <? echo ($BoxHeight - 2) ?>px;
            border: 0px solid white;
            border-top: 1px solid #3F7D91;
            border-bottom: 1px solid #3F7D91;
            float: left;
        }
        .day
        {
            width: <? echo ($BoxWidth - 2) ?>px;
            height: <? echo ($BoxHeight - 2) ?>px;
            border: 1px solid #3F7D91;
            float: left;
        }
        .today
        {
            background-color: #E7DDDD;
        }
        .lday /* Jour en conger, complété par .conger */
        {
            height: <? echo ($BoxHeight - 2)/2 ?>px;
        }
        .weekend
        {
            background-color: #E7EEEC;
        }
        .conger
        {
            height: <? echo ($BoxHeight - 2)/2 ?>px;
            width: 102px; /* selon le nombre de jours de conger */
            border: 1px solid blue;
            float: left;
        }
        .end
        {
            clear: both;
        }
    </style>
<? 

$Head = ob_get_clean();
ob_start();
$cal->WriteCalendar();
$Body = ob_get_clean();

if($download)
{
	// $Header: /cvsroot/html2ps/demo/html2ps.php,v 1.10 2007/05/17 13:55:13 Konstantin Exp $

	ini_set("display_errors","1");
	if (ini_get("pcre.backtrack_limit") < 1000000) { ini_set("pcre.backtrack_limit",1000000); };
	@set_time_limit(10000);

	require_once('html2pdf2/demo/generic.param.php');
	require_once('html2pdf2/config.inc.php');
	require_once(HTML2PS_DIR.'pipeline.factory.class.php');
	parse_config_file(HTML2PS_DIR.'html2ps.config');
	
	ini_set("user_agent", DEFAULT_USER_AGENT);
	
	$GLOBALS['g_config'] = array(
					 'compress'      => true,
					 'cssmedia'      => 'screen',
					 'debugbox'      => false,
					 'debugnoclip'   => false,
					 'draw_page_border'        => false,
					 'html2xhtml'    => false,
					 'landscape'     => true,
					 'margins'       => array(
											  'left'    => 5,
											  'right'   => 5,
											  'top'     => 20,
											  'bottom'  => 10,
											  ),
					 'media'         => "A4",
					 'method'        => "fpdf",
					 'mode'          => 'html',
					 'output'        => 1,
					 'pagewidth'     => 1024,
					 'pdfversion'    =>  "1.2",
					 'ps2pdf'        => true,
					 'pslevel'       => 3,
					 'renderfields'  => false,
					 'renderforms'   => false,
					 'renderimages'  => true,
					 'renderlinks'   => true,
					 'scalepoints'   => false,
					 'smartpagebreak' => true,
					 'transparency_workaround' => false
					 );
	$pipeline = PipelineFactory::create_default_pipeline("","");
	$pipeline->configure($g_config);
	$url = "http://127.0.0.1/kobukai/trunk/calendar/cal.php" . "?month=" . $cal->GetMonth() . "&year=" . $cal->GetYear() . "&pdf=1";
	
	$media = Media::predefined($GLOBALS['g_config']['media']);
	$media->set_landscape($GLOBALS['g_config']['landscape']);
	$media->set_margins($GLOBALS['g_config']['margins']);
	$media->set_pixels($GLOBALS['g_config']['pagewidth']);
	
	$pipeline->destination = new DestinationDownload("Calendrier 2007-2008");
	
	$pipeline->process($url, $media);
}
else if(0 && $download)
{
    $content = $Head . $Body;
    //echo $content;
    
    // convert in PDF
    require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'fr');
        //      $html2pdf->setModeDebug();
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('calendrier.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}
else
{
?>
<html>
	<head>
        <? echo $Head; ?>
	</head>
<body>
    
    <? if(!$pdf){ ?>
    <div class="navig">
        <a 	href="<? echo $PHP_SELF; ?>?month=0&year=0">Aujourd'hui</a>
        <a 	href="<? echo $PHP_SELF; ?>?month=<? echo $PCal->GetMonth(); ?>&year=<? echo $PCal->GetYear(); ?>">Précédent</a>
        <a 	href="<? echo $PHP_SELF; ?>?month=<? echo $NCal->GetMonth(); ?>&year=<? echo $NCal->GetYear(); ?>">Suivant</a>
    </div>
    <? } ?>
    
    <? echo $Body; ?>
    
    
    <? if(!$pdf){ ?>
    <div>
        <br/><br/>
        <a href="<? echo $PHP_SELF; ?>?download=1">pdf</a>
    </div>
    <? } ?>
</body>
</html>
<?
}
?>
