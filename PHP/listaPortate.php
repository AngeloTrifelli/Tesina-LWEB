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
    
    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Prenota servizio in camera: scegli portate</title>

        <style type="text/css">
            <?php include "../CSS/listaPortate.css" ?>
        </style>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&amp;display=swap" />
    </head>

    <body>

        <div class="containerCentrale">
        <h1>SCEGLI PORTATE:</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >
                <div class="mainArea">
                    <table>
                        <tr>
                            <td></td>
                            <td class="item alignCenter">Prezzo</td>
                            <td class="item alignCenter">Quantita</td>
                        </tr>
                        <tr>
                            <td><h2>ANTIPASTI:</h2></td>                          
                        </tr>
                        <tr>
                            <td class="content"><input type="checkbox" name="Antipasto di montagna" value="Antipasto di montagna" /> Antipasto di montagna</td>
                            <td class="content alignCenter">8 &euro;</td>
                            <td class="content alignCenter"><input type="number" class="textInput" name="Antipasto di montagna-quantita" /></td>
                        </tr>
                        <tr>
                            <td class="content"><input type="checkbox" name="Antipasto di mare" value="Antipasto di mare" /> Antipasto di mare</td>
                            <td class="content alignCenter">7 &euro;</td>
                            <td class="content alignCenter"><input type="number" class="textInput" name="Antipasto di mare-quantita" /></td>
                        </tr>
                        <tr>
                            <td><h2>PRIMI PIATTI:</h2></td>                            
                        </tr>
                        <tr>
                            <td class="content"><input type="checkbox" name="Spaghetti alla carbonare" value="Spaghetti alla carbonara" /> Spaghetti alla carbonara</td>
                            <td class="content alignCenter">8 &euro;</td>
                            <td class="content alignCenter"><input type="number" class="textInput" name="Spaghetti alla carbonara-quantita" /></td>
                        </tr>
                        <tr>
                            <td class="content"><input type="checkbox" name="Lasagne" value="Lasagne" /> Lasagne</td>
                            <td class="content alignCenter">9 &euro;</td>
                            <td class="content alignCenter"><input type="number" class="textInput" name="Lasagne-quantita" /></td>
                        </tr>
                        <tr>
                            <td><h2>SECONDI PIATTI:</h2></td>                           
                        </tr>
                        <tr>
                            <td class="content"><input type="checkbox" name="Tagliata di manzo" value="Tagliata di manzo" /> Tagliata di manzo</td>
                            <td class="content alignCenter">15 &euro;</td>
                            <td class="content alignCenter"><input type="number" class="textInput" name="Tagliata di manzo-quantita" /></td>
                        </tr>
                        <tr>
                            <td class="content"><input type="checkbox" name="Grigliata mista" value="Grigliata mista" /> Grigliata mista</td>
                            <td class="content alignCenter">20 &euro;</td>
                            <td class="content alignCenter"><input type="number" class="textInput" name="Grigliata mista-quantita" /></td>
                        </tr>
                        <tr>
                            <td><h2>DOLCI:</h2></td>                            
                        </tr>
                        <tr>
                            <td class="content"><input type="checkbox" name="Tiramisu" value="Tiramisu" /> Tiramisu</td>
                            <td class="content alignCenter">3 &euro;</td>
                            <td class="content alignCenter"><input type="number" class="textInput" name="Tiramisu-quantita" /></td>
                        </tr>
                        <tr>
                            <td class="content"><input type="checkbox" name="Sorbetto al limone" value="Sorbetto al limone" /> Sorbetto al limone</td>
                            <td class="content alignCenter">2 &euro;</td>
                            <td class="content alignCenter"><input type="number" class="textInput" name="Sorbetto al limone-quantita" /></td>
                        </tr>
                    </table> 
                   
                    <br />
                    <br />
                    <h2>Richieste aggiuntive:</h2>
                    <textarea type="text" class="textInput note" name="note"></textarea> 

                    <div class="zonaBottoni">
                        <input type="submit" class="button" name="ANNULLA" value="ANNULLA" />
                        <input type="submit" class="button large" name="CONFERMA" value="CONFERMA" />
                    </div>
                </div>
            </form>
        </div>


    </body>
</html>