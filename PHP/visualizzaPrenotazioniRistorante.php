<?php
    session_start();
    if(isset($_SESSION['codFiscUtenteLoggato'])){
        $temp = $_SESSION['soggiornoAttivo'];
        if($temp != "null"){
            if($temp['statoSoggiorno'] != "Approvato"){
                header('Location: areaUtente.php');
            }
        }
        else{
            header('Location: prenotaOra.php');
        }
    }
    else{
        header('Location: login.php');
    }

    if(isset($_POST['DETTAGLI'])){
        header('Location: dettagliPrenotazioneSC.php');
    }





    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Visualizza prenotazioni</title>

        <style>
            <?php include "../CSS/visualizzaPrenotazioniRistorante.css" ?>
        </style>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
    </head>


    <body>
        <div class="top">
            <div class="topLeft">
                <a href="./homeRistorante.php">TORNA INDIETRO</a>    
            </div>
            <h1 class="alignCenter">PRENOTAZIONI RISTORANTE</h1>
            <div style="width: 18.5%;"></div>
        </div>

        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >
            <h3 class="titoloImportante alignCenter">PRENOTAZIONI ATTIVE:</h3>
            <div class="mainContainer marginBottom">
                <table class="prenotazione alignCenter" align="center">
                    <tr>
                        <td>
                            <strong>Tipo prenotazione</strong><br />
                            Servizio al tavolo
                        </td>
                        <td>
                            <strong>Numero tavolo</strong><br />
                            2
                        </td>    
                        <td>
                            <strong>Locazione</strong><br />
                            Interna
                        </td>  
                        <td>
                            <strong>Data</strong><br />
                            30-10-2022
                        </td>    
                        <td>
                            <strong>Ora</strong><br />
                            21:30
                        </td>
                        <td class="hide">
                        </td>
                        <td>
                            <input type="submit" class="button" value="ANNULLA" />
                        </td>                                                      
                    </tr>
                </table>
                <table class="prenotazione alignCenter" align="center">
                    <tr>
                        <td class="primoCampo">
                            <strong>Tipo prenotazione</strong><br />
                            Servizio in camera
                        </td>
                        <td class="hide">
                        </td>
                        <td class="hide">
                        </td>
                        <td class="hide">
                        </td>
                        <td>
                            <strong>Data</strong><br />
                            30-10-2022
                        </td>    
                        <td>
                            <strong>Ora</strong><br />
                            12:30
                        </td>    
                        <td class="hide">
                        </td>
                        <td class="ultimoCampo">
                            <input type="submit" class="button" name="DETTAGLI" value="DETTAGLI" />
                        </td>                         
                    </tr>
                </table>
            </div>
        
            <h3 class="titoloImportante alignCenter">PRENOTAZIONI PASSATE:</h3>
            <div class="mainContainer marginBottom">
                    <table class="prenotazione alignCenter" align="center">
                        <tr>
                        <td>
                            <strong>Tipo prenotazione</strong><br />
                            Servizio al tavolo
                        </td>
                        <td>
                            <strong>Numero tavolo</strong><br />
                            10
                        </td>    
                        <td>
                            <strong>Locazione</strong><br />
                            Esterna
                        </td>  
                        <td>
                            <strong>Data</strong><br />
                            28-10-2022
                        </td>    
                        <td>
                            <strong>Ora</strong><br />
                            13:00
                        </td>           
                        <td class="hide">
                        </td>  
                        <td class="hide">
                        </td>         
                        </tr>
                    </table>
                    <table class="prenotazione alignCenter" align="center">
                    <tr>
                        <td class="primoCampo">
                            <strong>Tipo prenotazione</strong><br />
                            Servizio in camera
                        </td>
                        <td class="hide">
                        </td>
                        <td class="hide">
                        </td>
                        <td>
                            <strong>Data</strong><br />
                            28-10-2022
                        </td>    
                        <td>
                            <strong>Ora</strong><br />
                            20:30
                        </td>    
                        <td class="ultimoCampo">
                            <input type="submit" class="button" value="DETTAGLI" />
                        </td>                         
                    </tr>
                </table>
            </div>
        </form>
    </body>
</html>