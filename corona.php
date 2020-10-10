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

    function ToDateString($date)
    {
        return date("Y-m-d", $date);
    }

    $firstDate = strtotime("2020-08-31");
    $now = strtotime("now");

    while($firstDate <= $now)
    {
        $endDate = strtotime('+6 day', $firstDate);

        $attendees = pratiquants::GetPresencesBetweenTwoDates(ToDateString($firstDate), ToDateString($endDate));

        echo("<div class='title' style='width: 400px;margin-top: 50px;'>Participants entre " . ToDateString($firstDate) . " et " . ToDateString($endDate) . ":</div>");

        $mailBody = "";
        foreach ($attendees as $attendee)
        {
            $mail = "<div>";
            $mail .= $attendee->nom . " " . $attendee->prenom . "&nbsp;&nbsp;&nbsp;&nbsp;";
            $mail .= $attendee->email . "&nbsp;&nbsp;&nbsp;&nbsp;";
            $mail .= $attendee->telephone . "&nbsp;&nbsp;&nbsp;&nbsp;";
            $mail .= $attendee->gsm;
            $mail .= "<div>";

            echo($mail);

            $mailBody .= $attendee->nom . " " . $attendee->prenom;
            $mailBody .= "\t" . $attendee->email;
            $mailBody .= "\t" . $attendee->telephone;
            if($attendee->telephone == "")
                $mailBody .= "\t" . $attendee->gsm;
            $mailBody .= "\n";
        }

        $to = "sport@neupre.be";
        $subject = "Participants au cours d%27aïkido entre le " . ToDateString($firstDate) . " et le " . ToDateString($endDate);
        echo("<div><a href='mailto:" . $to . "?subject=". $subject ."&body=" . urlencode($mailBody). "' target='_blank'>mail</a></div>");

        $firstDate = strtotime('+1 week', $firstDate);
    }
    ?>
</div>

</body>
</html>