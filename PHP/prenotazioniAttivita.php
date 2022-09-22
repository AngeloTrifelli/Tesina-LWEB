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
        <title>Prenotazioni attivit&agrave;</title>

        <style>
            <?php include "../CSS/prenotazioniAttivita.css" ?>
        </style>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
    </head>


    <body>
        <div class="top">

            <div class="topLeft">               
                <a href="./prenotazioniClienti.php">TORNA INDIETRO</a>  
            </div>

            <h1 class="alignCenter">LISTA PRENOTAZIONI ATTIVIT&Agrave;</h1>
           
            <div style="width: 18.5%;"></div>
               
        </div>

    
        <h3 class="titoloImportante alignCenter">PRENOTAZIONI ATTIVE:</h3>
        <div class="mainContainer marginBottom">
            <div class="areaDati">
                <table class="alignCenter tabella"  align="center">
                    <tr>
                        <td><strong>Codice fiscale cliente</strong></td>
                        <td><strong>Nome attivita</strong></td>
                        <td><strong>Data</strong></td>
                        <td><strong>Ora inizio</strong></td>
                        <td><strong>Ora fine</strong></td>
                        <td><strong>Prezzo totale</strong></td>
                        <td><strong>Crediti usati</strong></td>
                    </tr>
                    <tr>
                        <td>RSSGNN64R03E472G</td>
                        <td>Palestra</td>
                        <td>29-10-2022</td>
                        <td>17:00</td>
                        <td>19:00</td>
                        <td>20 &euro;</td>
                        <td>0</td>
                        <td><input type="submit" class="button" name="MODIFICA" value="MODIFICA" />  </td>
                        <td><input type="submit" class="button" name="ANNULLA" value="ANNULLA" />  </td>
                    </tr>
                </table>
            </div>
        </div>

        <h3 class="titoloImportante alignCenter">PRENOTAZIONI PASSATE:</h3>
        <div class="mainContainer marginBottom">
            <div class="areaDati">
                <table class="alignCenter tabella"  align="center">
                    <tr>
                        <td><strong>Codice fiscale cliente</strong></td>
                        <td><strong>Nome attivita</strong></td>
                        <td><strong>Data</strong></td>
                        <td><strong>Ora inizio</strong></td>
                        <td><strong>Ora fine</strong></td>
                        <td><strong>Prezzo totale</strong></td>
                        <td><strong>Crediti usati</strong></td>
                    </tr>
                    <tr>
                        <td>RSSGNN64R03E472G</td>
                        <td>Spa</td>
                        <td>15-06-2022</td>
                        <td>16:00</td>
                        <td>17:00</td>
                        <td>30 &euro;</td>
                        <td>0</td>
                    </tr>
                </table>
            </div>
        </div>
    
       
    </body>
</html>