/*
	v liste tous pratiquants
	v liste par section
	v recherche par nom
	- recherche par nom et section
	v passage de grade par personne
	v grade actuel
	v liste les chefs pour correspondance
	v liste des expirations
	- pret à presenter à un nb de cours pret
	- ajout d'une présence
	- pratiquants qui doivent changer de section
*/


<?php

	require_once("core/pmo/PMO_core/PMO_MyController.php");
	require_once("core/pmo/PMO_core/class_loader/class_pratiquants.php");

/*	$controler = new PMO_MyController();
	$map = $controler->queryController("SELECT * FROM pratiquants;");
	
	while ($result = $map->fetchMap()){
		echo($result['pratiquants']->nom);
		echo('<br>');
		//echo($result['pratiquants']->GetNom());
	}
test de modif
/*
	$prat = PMO_MyObject::factory('pratiquants');
	$prat->id = 1;
	$prat->load();
	echo($prat->prenom . "<br>");
	echo($prat->IsFamille()?"famille":"pas famille");
	echo("<br>");
	if($prat->IsFamille())
	{
		$chef = $prat->GetChefFamille();
		if($chef != null)
			echo($chef->prenom);
		else
			echo("oups");
		
		echo("<br>");
		echo($chef->IsFamille()?"famille":"pas famille");
	}
*/
	$pratiquants = pratiquants::GetAll();
	
	if(count($pratiquants) != 0)
	{
		foreach($pratiquants as $prat)
		{
			echo($prat->nom . " " . $prat->prenom . " " . ($prat->IsFamille()?"famille":"pas famille"));
			if($prat->GetGrade() != NULL)
				echo(" " . $prat->GetGrade()->GetGrade()->$libelle);
			else
				echo(" pas grade");
				
			if($prat->GetSection() != NULL)
				echo(" " . $prat->GetSection()->libelle);
			else
				echo(" pas section");
			echo("<br>");
		}
	}
	else
	{
		echo("pas de pratiquants");
	}
	
	echo("<br>section<br>");
	$pratiquants = pratiquants::GetBySection(1);
	
	if(count($pratiquants) != 0)
	{
		foreach($pratiquants as $prat)
		{
			//echo($prat->IsFamille());
			echo($prat->nom . " " . $prat->prenom . " " . ($prat->IsFamille()?"famille":"pas famille"));
			echo("<br>");
		}
	}
	else
	{
		echo("pas de pratiquants");
	}
	
	echo("<br>chefs<br>");
	$pratiquants = pratiquants::GetChefs();
	
	if(count($pratiquants) != 0)
	{
		foreach($pratiquants as $prat)
		{
			//echo($prat->IsFamille());
			echo($prat->nom . " " . $prat->prenom . " " . ($prat->IsFamille()?"famille":"pas famille"));
			echo("<br>");
		}
	}
	else
	{
		echo("pas de pratiquants");
	}
	
?>