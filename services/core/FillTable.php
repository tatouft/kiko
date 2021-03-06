<?php
    function FillTable($action, $section)
    {
        if($action == "all")
		{
            $pratiquants = pratiquants::GetAll();
		}
        else if($action == "section")
		{
            $pratiquants = pratiquants::GetBySection($section);
		}
        else if($action == "examens")
		{
            $pratiquants = pratiquants::GetByExam($section);
		}
        else if($action == "license")
        {
            $pratiquants = pratiquants::GetExpired();
        }
        else if($action == "poubelle")
		{
            $pratiquants = pratiquants::GetPoubelle($section);
		}
        else if($action == "mail")
		{
            $pratiquants = pratiquants::GetChefs($section);
		}
		else
		{
			$pratiquants = pratiquants::GetAll();
		}
		
        return $pratiquants;
    }
?>