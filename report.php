<?php
	require_once("config/config.php");
	require_once("core/pmo/PMO_core/PMO_MyController.php");
	require_once("core/pmo/PMO_core/class_loader/class_pratiquants.php");
?>
<html>
	<head>
		<script src="js/scriptaculous/prototype.js"		type="text/javascript"></script>
		<script src="js/scriptaculous/scriptaculous.js"	type="text/javascript"></script>
		<script src="js/action.js"						type="text/javascript"></script>
		
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="css/general.css" type="text/css">
	</head>
	<body>
		<?php
                    $CurrentPage = "lists";
                    require_once("controls/PageHeader.php"); 
		?>
		<div id="debug">&nbsp;</div>
		<div>
			<?php 
				$lastYeardate1 = "2017-09-01";
				$lastYeardate2 = "2018-07-01";
				$thisYeardate1 = "2018-09-01";
				$thisYeardate2 = "2019-07-01";
			?>
			<div class="title">Affiliés 2017-2018:</div> <div class="value"><?php echo(pratiquants::GetCount($lastYeardate1, $lastYeardate2)); ?></div><br/>
			<div class="title">Affiliés 2018-2019:</div> <div class="value"><?php echo(pratiquants::GetCount($thisYeardate1, $thisYeardate2)); ?></div><br/>
			<div class="title">Affiliés de neupré:</div> <div class="value"><?php echo(pratiquants::GetCountNeupre($thisYeardate1, $thisYeardate2)); ?></div><br/>
			<div class="title"><?php $a1=2;  $a2=5;   ?> Affiliés <?php echo($a1 . '-' . $a2);?>:</div> <div class="value"><?php echo(pratiquants::GetCountAge( $thisYeardate1, $thisYeardate2, $a1, $a2, 0)); ?> femmes, <?php echo(pratiquants::GetCountAge($thisYeardate1, $thisYeardate2, $a1, $a2, 1)); ?> hommes</div><br/>
			<div class="title"><?php $a1=6;  $a2=11;  ?> Affiliés <?php echo($a1 . '-' . $a2);?>:</div> <div class="value"><?php echo(pratiquants::GetCountAge( $thisYeardate1, $thisYeardate2, $a1, $a2, 0)); ?> femmes, <?php echo(pratiquants::GetCountAge($thisYeardate1, $thisYeardate2, $a1, $a2, 1)); ?> hommes</div><br/>
			<div class="title"><?php $a1=12; $a2=18;  ?> Affiliés <?php echo($a1 . '-' . $a2);?>:</div> <div class="value"><?php echo(pratiquants::GetCountAge( $thisYeardate1, $thisYeardate2, $a1, $a2, 0)); ?> femmes, <?php echo(pratiquants::GetCountAge($thisYeardate1, $thisYeardate2, $a1, $a2, 1)); ?> hommes</div><br/>
			<div class="title"><?php $a1=19; $a2=35;  ?> Affiliés <?php echo($a1 . '-' . $a2);?>:</div> <div class="value"><?php echo(pratiquants::GetCountAge( $thisYeardate1, $thisYeardate2, $a1, $a2, 0)); ?> femmes, <?php echo(pratiquants::GetCountAge($thisYeardate1, $thisYeardate2, $a1, $a2, 1)); ?> hommes</div><br/>
			<div class="title"><?php $a1=36; $a2=60;  ?> Affiliés <?php echo($a1 . '-' . $a2);?>:</div> <div class="value"><?php echo(pratiquants::GetCountAge( $thisYeardate1, $thisYeardate2, $a1, $a2, 0)); ?> femmes, <?php echo(pratiquants::GetCountAge($thisYeardate1, $thisYeardate2, $a1, $a2, 1)); ?> hommes</div><br/>
			<div class="title"><?php $a1=60; $a2=150; ?> Affiliés <?php echo($a1 . '-' . $a2);?>:</div> <div class="value"><?php echo(pratiquants::GetCountAge( $thisYeardate1, $thisYeardate2, $a1, $a2, 0)); ?> femmes, <?php echo(pratiquants::GetCountAge($thisYeardate1, $thisYeardate2, $a1, $a2, 1)); ?> hommes</div><br/>
		</div>		
		
	</body>
</html>