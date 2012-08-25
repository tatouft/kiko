<?php
    require('config/config.php');
	require_once("core/pmo/PMO_core/PMO_MyController.php");
	require_once("core/pmo/PMO_core/class_loader/class_pratiquants.php");
    require('fpdf/CellPDF.php');
    require_once("services/core/FillTable.php");


    class PDFTable extends CellPDF
    {
        
        // Tableau amélioré
        function Table($config, $data)
        {
            $i = 0;
            // Données
            foreach($data as $row)
            {
                $this->Cell($config->CellWidth, $config->CellHeight, $row, '', '0', 'C');
                $i++;
                
                if($i >= $config->NbCellByLine)
                {
                    $this->Ln();
                    $i = 0;
                }
            }
        }
    }
    
    $pdf = new PDFTable();
    $pdf->SetMargins(0,0);
    $pdf->AddPage();
    $pdf->SetFont('Arial','',12);
    
    $action = $_REQUEST['action'];
	$section = $_REQUEST['section'];
    $pratiquants = FillTable($action, $section);

    
    $i = 0;
    foreach($pratiquants as $prat)
    {
        $nom = ucfirst($prat->nom) . " " . ucfirst($prat->prenom);
        $ville = $prat->codePostal . " " . $prat->commune;
        $data[$i] = utf8_decode($nom) . "\n" . utf8_decode($prat->adresse) . "\n" . utf8_decode($ville);
        $i++;
    }
    
    $pdf->Table($pdfConfig, $data);

    
    
    $pdf->Output();
    
    
?>