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
        <title>Lista orari</title>

        <style>
            <?php include "../CSS/listaOrari.css" ?>
        </style>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
    </head>


    <body>
        <div class="top">

            <div class="topLeft">               
                <a href="./areaUtente.php">TORNA INDIETRO</a>  
            </div>

            <h1 class="alignCenter">LISTA ORARI</h1>
           
            <div style="width: 18.5%;"></div>
               
        </div>

    
        <h3 class="titoloImportante alignCenter">RISTORANTE:</h3>
        <div class="mainContainer marginBottom">
            <div class="areaDati">
                <table class="alignCenter tabella"  align="center">
                    <tr>
                        <td><strong>Apertura pranzo</strong></td>
                        <td><strong>Chiusura pranzo</strong></td>
                        <td><strong>Apertura cena</strong></td>
                        <td><strong>Chiusura cena</strong></td>
                    </tr>
                    <tr>
                        <td>12:00</td>
                        <td>15:00</td>
                        <td>19:00</td>
                        <td>23:00</td>
                        <td><input type="submit" class="button" name="RISTORANTE" value="MODIFICA" />  </td>
                    </tr>
                </table>
            </div>
        </div>

        <h3 class="titoloImportante alignCenter">ATTIVIT&Agrave;:</h3>
        <div class="mainContainer marginBottom">
            <div class="areaDati">
                <table class="alignCenter tabella"  align="center">
                    <tr>
                        <td><strong>Nome</strong></td>
                        <td><strong>Apertura</strong></td>
                        <td><strong>Chiusura</strong></td>
                    </tr>
                    <tr>
                        <td>Palestra</td>
                        <td>06:00</td>
                        <td>22:00</td>
                        <td><input type="submit" class="button" name="RISTORANTE" value="MODIFICA" />  </td>
                    </tr>
                </table>
            </div>
        </div>
    
       
    </body>
</html>