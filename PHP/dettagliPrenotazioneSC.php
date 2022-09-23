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

    if(isset($_POST['INDIETRO'])){
        header('Location: visualizzaPrenotazioniRistorante.php');
    }





    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
    
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Dettagli prenotazione</title>

        <style type="text/css">
            <?php include "../CSS/dettagliPrenotazioneSC.css" ?>
        </style>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&amp;display=swap" />
    </head>

    <body>

        <div class="containerCentrale">
        <h1>DETTAGLI PRENOTAZIONE:</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >
                <div class="mainArea">
                    <table>
                        <tr>
                            <td class="item">Nome portata</td>
                            <td class="item">Prezzo</td>
                            <td class="item">Quantita</td>
                            <td class="item">Prezzo totale</td>
                        </tr>
                        <tr>
                            <td class="content">Spaghetti alla carbonara</td>
                            <td class="content">8 &euro;</td>
                            <td class="content">2</td>
                            <td class="content">16 &euro;</td>
                        </tr>
                        <tr>
                            <td class="content">Tiramisu</td>
                            <td class="content">3 &euro;</td>
                            <td class="content">1</td>
                            <td class="content">3 &euro;</td>
                        </tr>
                        <tr>
                            <td class="content">Sorbetto al limone</td>
                            <td class="content">2 &euro;</td>
                            <td class="content">1</td>
                            <td class="content">2 &euro;</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><hr /></td>
                            <td><hr /></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="item">Totale complessivo:</td>
                            <td class="content">21 &euro;</td>
                        </tr>
                    </table> 
                   

                    <div class="zonaBottoni">
                        <input type="submit" class="button" name="INDIETRO" value="TORNA INDIETRO" />
                        <input type="submit" class="button large" name="ANNULLA" value="ANNULLA PRENOTAZIONE" />
                    </div>
                </div>
            </form>
        </div>


    </body>
</html>