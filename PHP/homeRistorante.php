<?php
    session_start();
    if(isset($_SESSION['codFiscUtenteLoggato'])){
        $temp = $_SESSION['soggiornoAttivo'];
        if($temp != "null"){
            if($temp['statoSoggiorno'] != "Approvato"){
                header('Location: areaUtente.php');
                exit();
            }
        }
        else{
            header('Location: prenotaOra.php');
            exit();
        }
    }
    else{
        header('Location: login.php');
        exit();
    }

    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">


    <head>
        <title>Sapienza hotel: Servizio di ristorazione</title>
        <style>
            <?php include "../CSS/homeRistorante.css" ?>
        </style>
    
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet" /> 
    </head>

    <body>


        <div id="leftColumn">
                     
            <img id="logo" src="https://upload.wikimedia.org/wikipedia/it/thumb/0/0d/Uniroma1.svg/2560px-Uniroma1.svg.png" alt="Immagine non caricata"/>


            <div id="links">
            
                <a class="item" href="./prenotaTavolo.php">PRENOTA UN TAVOLO</a>
                <br />
                <a class="item" href="./prenotaSC.php">PRENOTA SERVIZIO IN CAMERA</a>
                <br />
                <a class="item" href="./visualizzaPrenotazioniRistorante.php">VISUALIZZA PRENOTAZIONI</a>
                <br />
                <a class="item" href="./visualizzaMenu.php">VISUALIZZA MEN&Ugrave;</a>
                <br />
                <br />
                <a class="item" href="./areaUtente.php">TORNA ALL'AREA PERSONALE</a>
        
            </div>
            
        </div>

    <div id="rightColumn">

        <h1 class="mainTitle">SERVIZIO DI RISTORAZIONE</h1>
        <img id="img" src="../img/ristorante.jpg" alt="Immagine non trovata"/>
            
    </div>


    </body>

</html>