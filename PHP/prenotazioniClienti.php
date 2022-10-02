<?php
    session_start();
    if(!isset($_SESSION['codFiscUtenteLoggato'])){
        if(!isset($_SESSION['loginType'])){
            header('Location: intro.php');
        }
    }
    else{
        header('Location: areaUtente.php');
    }

    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">


    <head>
        <title>Prenotazioni clienti</title>
        <style>
            <?php include "../CSS/prenotazioniClienti.css" ?>
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
                <a class="item" href="./prenotazioniRistorante.php">PRENOTAZIONI RISTORANTE</a>
                <br />
                <a class="item" href="./listaPrenotazioniAttivita.php">PRENOTAZIONI ATTIVIT&Agrave;</a>
                <br />
                <br />
                <a class="item" href="./areaUtente.php">TORNA ALL'AREA PERSONALE</a>
            </div>
            
        </div>

        <div id="rightColumn">

            <h1 class="mainTitle">PRENOTAZIONI CLIENTI</h1>       
            <img id="img" src="../img/hotel.jpg" alt="Immagine non trovata"/>
                
        </div>


    </body>

</html>