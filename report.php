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
				$lastYeardate1 = "2019-09-01";
				$lastYeardate2 = "2020-07-01";
				$thisYeardate1 = "2020-09-01";
				$thisYeardate2 = "2021-07-01";
			?>
			<div class="title">Affiliés 2019-2020:</div> <div class="value"><?php echo(pratiquants::GetCount($lastYeardate1, $lastYeardate2)); ?></div><br/>
			<div class="title">Affiliés 2020-2021:</div> <div class="value"><?php echo(pratiquants::GetCount($thisYeardate1, $thisYeardate2)); ?></div><br/>
			<div class="title">Affiliés de neupré:</div> <div class="value"><?php echo(pratiquants::GetCountNeupre($thisYeardate1, $thisYeardate2)); ?></div><br/>
			<div class="title"><?php $a1=2;  $a2=5;   ?> Affiliés <?php echo($a1 . '-' . $a2);?>:</div> <div class="value"><?php echo(pratiquants::GetCountAge( $thisYeardate1, $thisYeardate2, $a1, $a2, 0)); ?> femmes, <?php echo(pratiquants::GetCountAge($thisYeardate1, $thisYeardate2, $a1, $a2, 1)); ?> hommes, <?php echo(pratiquants::GetCountAgeNeupre($thisYeardate1, $thisYeardate2, $a1, $a2)); ?> Neurpéens</div><br/>
			<div class="title"><?php $a1=6;  $a2=8;  ?> Affiliés <?php echo($a1 . '-' . $a2);?>:</div> <div class="value"><?php echo(pratiquants::GetCountAge( $thisYeardate1, $thisYeardate2, $a1, $a2, 0)); ?> femmes, <?php echo(pratiquants::GetCountAge($thisYeardate1, $thisYeardate2, $a1, $a2, 1)); ?> hommes, <?php echo(pratiquants::GetCountAgeNeupre($thisYeardate1, $thisYeardate2, $a1, $a2)); ?> Neurpéens</div><br/>
			<div class="title"><?php $a1=9; $a2=12;  ?> Affiliés <?php echo($a1 . '-' . $a2);?>:</div> <div class="value"><?php echo(pratiquants::GetCountAge( $thisYeardate1, $thisYeardate2, $a1, $a2, 0)); ?> femmes, <?php echo(pratiquants::GetCountAge($thisYeardate1, $thisYeardate2, $a1, $a2, 1)); ?> hommes, <?php echo(pratiquants::GetCountAgeNeupre($thisYeardate1, $thisYeardate2, $a1, $a2)); ?> Neurpéens</div><br/>
			<div class="title"><?php $a1=13; $a2=17;  ?> Affiliés <?php echo($a1 . '-' . $a2);?>:</div> <div class="value"><?php echo(pratiquants::GetCountAge( $thisYeardate1, $thisYeardate2, $a1, $a2, 0)); ?> femmes, <?php echo(pratiquants::GetCountAge($thisYeardate1, $thisYeardate2, $a1, $a2, 1)); ?> hommes, <?php echo(pratiquants::GetCountAgeNeupre($thisYeardate1, $thisYeardate2, $a1, $a2)); ?> Neurpéens</div><br/>
			<div class="title"><?php $a1=18; $a2=21;  ?> Affiliés <?php echo($a1 . '-' . $a2);?>:</div> <div class="value"><?php echo(pratiquants::GetCountAge( $thisYeardate1, $thisYeardate2, $a1, $a2, 0)); ?> femmes, <?php echo(pratiquants::GetCountAge($thisYeardate1, $thisYeardate2, $a1, $a2, 1)); ?> hommes, <?php echo(pratiquants::GetCountAgeNeupre($thisYeardate1, $thisYeardate2, $a1, $a2)); ?> Neurpéens</div><br/>
            <div class="title"><?php $a1=18; $a2=21;  ?> Affiliés <?php echo($a1 . '-' . $a2);?>:</div> <div class="value"><?php echo(pratiquants::GetCountAge( $thisYeardate1, $thisYeardate2, $a1, $a2, 0)); ?> femmes, <?php echo(pratiquants::GetCountAge($thisYeardate1, $thisYeardate2, $a1, $a2, 1)); ?> hommes, <?php echo(pratiquants::GetCountAgeNeupre($thisYeardate1, $thisYeardate2, $a1, $a2)); ?> Neurpéens</div><br/>
            <div class="title"><?php $a1=22; $a2=34;  ?> Affiliés <?php echo($a1 . '-' . $a2);?>:</div> <div class="value"><?php echo(pratiquants::GetCountAge( $thisYeardate1, $thisYeardate2, $a1, $a2, 0)); ?> femmes, <?php echo(pratiquants::GetCountAge($thisYeardate1, $thisYeardate2, $a1, $a2, 1)); ?> hommes, <?php echo(pratiquants::GetCountAgeNeupre($thisYeardate1, $thisYeardate2, $a1, $a2)); ?> Neurpéens</div><br/>
            <div class="title"><?php $a1=35; $a2=49;  ?> Affiliés <?php echo($a1 . '-' . $a2);?>:</div> <div class="value"><?php echo(pratiquants::GetCountAge( $thisYeardate1, $thisYeardate2, $a1, $a2, 0)); ?> femmes, <?php echo(pratiquants::GetCountAge($thisYeardate1, $thisYeardate2, $a1, $a2, 1)); ?> hommes, <?php echo(pratiquants::GetCountAgeNeupre($thisYeardate1, $thisYeardate2, $a1, $a2)); ?> Neurpéens</div><br/>
            <div class="title"><?php $a1=50; $a2=64;  ?> Affiliés <?php echo($a1 . '-' . $a2);?>:</div> <div class="value"><?php echo(pratiquants::GetCountAge( $thisYeardate1, $thisYeardate2, $a1, $a2, 0)); ?> femmes, <?php echo(pratiquants::GetCountAge($thisYeardate1, $thisYeardate2, $a1, $a2, 1)); ?> hommes, <?php echo(pratiquants::GetCountAgeNeupre($thisYeardate1, $thisYeardate2, $a1, $a2)); ?> Neurpéens</div><br/>
            <div class="title"><?php $a1=65; $a2=74;  ?> Affiliés <?php echo($a1 . '-' . $a2);?>:</div> <div class="value"><?php echo(pratiquants::GetCountAge( $thisYeardate1, $thisYeardate2, $a1, $a2, 0)); ?> femmes, <?php echo(pratiquants::GetCountAge($thisYeardate1, $thisYeardate2, $a1, $a2, 1)); ?> hommes, <?php echo(pratiquants::GetCountAgeNeupre($thisYeardate1, $thisYeardate2, $a1, $a2)); ?> Neurpéens</div><br/>
			<div class="title"><?php $a1=75; $a2=150; ?> Affiliés <?php echo($a1 . '-' . $a2);?>:</div> <div class="value"><?php echo(pratiquants::GetCountAge( $thisYeardate1, $thisYeardate2, $a1, $a2, 0)); ?> femmes, <?php echo(pratiquants::GetCountAge($thisYeardate1, $thisYeardate2, $a1, $a2, 1)); ?> hommes, <?php echo(pratiquants::GetCountAgeNeupre($thisYeardate1, $thisYeardate2, $a1, $a2)); ?> Neurpéens</div><br/>
		</div>		
		
	</body>
</html>