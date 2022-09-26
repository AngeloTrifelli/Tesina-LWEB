<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniInsertPHP.php');

    session_start();

    if(isset($_POST['confermaPrenotazione'])){
        if(isset($_POST['ANNULLA'])){       
            unset($_SESSION['richiestaSC']);
            header('Location: homeRistorante.php');
            exit(); 
        }
        else{
            $temp = $_SESSION['richiestaSC'];
            inserisciPrenotazioneServizioCamera($temp['portateScelte'] , $_SESSION['codFiscUtenteLoggato'], $temp['dataPrenotazione'] , $temp['oraPrenotazione'] , $temp['richiesteAggiuntive'] , $_POST['prezzoPrenotazione'] , $_POST['creditiUsati']);
            unset($_SESSION['richiestaSC']);
            header('Location: registrazioneCompletata.php');
            exit();            
        }        
    }
    
    if(isset($_SESSION['richiestaSC'])){
        if(isset($_SESSION['accessoPermesso'])){
            $temp = $_SESSION['richiestaSC'];
            $portateScelte = $temp['portateScelte'];
            $dataPrenotazione = $temp['dataPrenotazione'];
            $oraPrenotazione = $temp['oraPrenotazione'];
            $richieste = $temp['richiesteAggiuntive'];
            
            $cliente = getDatiCliente($_SESSION['codFiscUtenteLoggato']);

            $confermaPrenotazione = "True";    
            unset($_SESSION['accessoPermesso']);   
        }
        else{
            unset($_SESSION['richiestaSC']);
            header('Location: areaUtente.php');
            exit();
        }
    }
    else{
        header('Location: areaUtente.php');
        exit();
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

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&amp;display=swap" />
    </head>

    <body>

        <div class="containerCentrale">
        <h1>DETTAGLI PRENOTAZIONE:</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >
                <div class="mainArea">
                <?php
                    if(isset($confermaPrenotazione)){
                ?>
                        <div class="row">
                            <span class="info">
                                Data prenotazione:
                            <?php                            
                                $giorno = substr($dataPrenotazione, 8,2);       
                                $mese = substr($dataPrenotazione,5,2 );
                                $anno = substr($dataPrenotazione,0,4 );
                                echo $giorno."-".$mese."-".$anno;
                            ?>
                            </span>
                            <span class="info">
                                Ora prenotazione: <?php echo substr($oraPrenotazione, 0 , 5);?>                                
                            </span>
                        </div>
                <?php
                    }
                ?>
                    <table>
                        <tr>
                            <td class="item">Nome portata</td>
                            <td class="item">Prezzo</td>
                            <td class="item">Quantita</td>
                            <td class="item">Prezzo totale</td>
                        </tr>
                    <?php
                        $numPortate = count($portateScelte);
                        $prezzoPrenotazione = 0;
                        for($i=0 ; $i < $numPortate ; $i++){
                            $portata = $portateScelte[$i];                            
                    ?>
                            <tr>
                                <td class="content"><?php echo $portata['descrizione'];?></td>
                                <td class="content"><?php echo $portata['prezzo'];?> &euro;</td>
                                <td class="content"><?php echo $portata['quantita'];?></td>
                                <td class="content">
                            <?php
                                $prezzoTotale = $portata['quantita'] * $portata['prezzo'];
                                $prezzoPrenotazione += $prezzoTotale;
                                echo $prezzoTotale."&euro;";
                            ?>
                                </td>
                            </tr>  
                    <?php
                        }
                    ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><hr /></td>
                            <td><hr /></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="item">Totale prenotazione:</td>
                            <td class="content"><?php echo $prezzoPrenotazione;?> &euro;</td>
                        </tr>                    
                        <tr>
                            <td></td>
                            <td></td>
                        <?php
                            if(isset($confermaPrenotazione)){
                        ?>
                                <td class="item">Crediti disponibili:</td>
                                <td class="content"><?php echo $cliente['crediti'];?></td>
                        <?php
                            }
                        ?>
                        </tr>
                        <?php
                            if(isset($confermaPrenotazione)){
                        ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td class="item">Utilizza crediti:</td>
                                    <td class="content"><input type="number" name="creditiUsati" value="0"  id="textInput" autocomplete="off" /></td>
                                </tr>
                        <?php
                            }
                        ?>
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
                            <td class="content"><span id="totComplessivo"><?php echo $prezzoPrenotazione;?> &euro;</span></td>
                        </tr>
                    </table> 

                    <span class="info">
                        Richieste aggiuntive: 
                    </span>                        
                    <div class="info">
                        <?php echo $richieste;?>
                    </div>                   
                    

                    <div class="zonaBottoni">
                    <?php
                        if(isset($confermaPrenotazione)){
                    ?>
                            <input type="submit" class="button" name="ANNULLA" value="ANNULLA" />
                            <input type="submit" class="button" name="CONFERMA" value="CONFERMA" />
                    <?php
                        }
                    ?>
                    </div>
                </div>
                <?php
                    if(isset($confermaPrenotazione)){
                ?>
                        <input type="hidden" name="confermaPrenotazione" value="confermaPrenotazione" />
                        <input type="hidden" name="prezzoPrenotazione" value="<?php echo $prezzoPrenotazione;?>" />
                <?php        
                    }
                ?>            
            </form>
        </div>


        <?php
            if(isset($confermaPrenotazione)){
        ?>
                <script>
                    var maxCrediti = <?php echo json_encode($cliente['crediti']); ?>;            
                    var maxValue = <?php echo json_encode($prezzoPrenotazione); ?>;            
                    $("#textInput").on('change' , function(e){
                        if(e.target.value < 0){
                            e.target.value = 0;
                        }

                        if(e.target.value > maxCrediti){
                            e.target.value = maxCrediti; 
                        }
                        
                        var prezzoDaSottrarre = e.target.value / 5;

                        var totComplessivo = maxValue - prezzoDaSottrarre;
                        var encodedStr = totComplessivo + " &euro;";
                        var decoded = $("<div/>").html(encodedStr).text();
                        $("#totComplessivo").text(decoded);
                    });
                </script> 
        <?php
            }
        ?>

    </body>
</html>