<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniInsertPHP.php');
    session_start();

    if(isset($_SESSION['prenotazioneCamera'])){         // Poi questo si modifica per adattare la pagina ad altre prenotazioni
        if(isset($_SESSION['soggiornoAttivo']) && $_SESSION['soggiornoAttivo'] == "null"){ 
            $temp = $_SESSION['prenotazioneCamera'];
            $datiCamera = getCamera($temp['idCamera']);
            $cliente = getDatiCliente($_SESSION['codFiscUtenteLoggato']);

            $prezzoCamera = $datiCamera['prezzo'];

            $temp1 = new DateTime($temp['dataArrivo']);
            $temp2 = new DateTime($temp['dataPartenza']);

            $giorniSoggiorno = $temp1->diff($temp2)->format("%a");
            $prezzoTotale = $giorniSoggiorno * $prezzoCamera;

            unset($_SESSION['prenotazioneCamera']);
        }
        else{
            header('Location: intro.php');
        }
        
    }
    elseif((!(isset($_POST['idAttivita']))) && (!(isset($_SESSION['prenotazioneAttivita'])))){
        if(isset($_POST['ANNULLA']) || isset($_POST['CONFERMA'])){
            if(isset($_POST['ANNULLA'])){
                unset($_SESSION['prenotazioneCamera']);
                header('Location: prenotaOra.php');
            }
            else{
                inserisciPrenotazioneCamera($_POST['idCamera'] , $_SESSION['codFiscUtenteLoggato'] , $_POST['creditiUsati'] , $_POST['dataArrivo'] , $_POST['dataPartenza']);
                $_SESSION['soggiornoAttivo'] = getSoggiornoAttivo($_SESSION['codFiscUtenteLoggato']);
                header('Location: registrazioneCompletata.php');
            }
        }
        else{
            header('Location: intro.php');
        }          
    }

    if(isset($_SESSION['prenotazioneAttivita'])){
        if(isset($_SESSION['soggiornoAttivo']) && $_SESSION['soggiornoAttivo'] != "null"){ 
            $temp = $_SESSION['prenotazioneAttivita'];
            $datiAttivita=getDatiAttivita($temp['idAttivita']);
            $cliente = getDatiCliente($_SESSION['codFiscUtenteLoggato']);

            $oraInizio=$temp['oraInizio'];
            $oraFine=$temp['oraFine'];
            settype($oraInizio,"integer");
            settype($oraFine,"integer");

            $oraDiAttivita=$oraFine-$oraInizio;

            $prezzoOrario=$datiAttivita['prezzoOrario'];
            settype($prezzoOrario,"integer");

            $prezzoTotale=$prezzoOrario*$oraDiAttivita;

            unset($_SESSION['prenotazioneAttivita']);

    }else{
        header('Location: intro.php');
    }
}else{
    if(isset($_POST['ANNULLA']) || isset($_POST['CONFERMA'])){
        if(isset($_POST['ANNULLA'])){
            $_SESSION['idAttivita']=$_POST['idAttivita'];
            unset($_SESSION['prenotazioneAttivita']);
            header('Location: prenotaAttivita.php');
        }
        else{
            aggiungiPrenotazioneAttivita($_POST['idAttivita'] , $_SESSION['codFiscUtenteLoggato'] , $_POST['dataAttivita'] , $_POST['oraInizio'], $_POST['oraFine'],$_POST['prezzoTotale'], $_POST['creditiUsati']);
            header('Location: registrazioneCompletata.php');
        }
    }
    else{
        header('Location: intro.php');
    }          
}
   

   





    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Conferma prenotazione</title>

        <style type="text/css">
            <?php include "../CSS/confermaPrenotazione.css" ?>
        </style>

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&amp;display=swap" />
    </head>

    <body>
        <div class="containerCentrale">
        <h1>CONFERMA PRENOTAZIONE:</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >

                <div class="mainArea">
                <?php
                    if(isset($datiCamera)){
                ?>
                    <div class="zonaSuperiore">
                        
                        <div class="zonaDati">
                            <span class="item">Numero camera: <?php echo substr($temp['idCamera'] , 1);?></span>
                            <span class="item">Tipo di camera: <?php echo $datiCamera['tipoCamera'];?></span>
                            <span class="item">Data di arrivo:
                                <?php
                                    $stringaData = $temp['dataArrivo'];
                                    $giorno = substr($stringaData, 8,2);       
                                    $mese = substr($stringaData,5,2 );
                                    $anno = substr($stringaData,0,4 );
                                    echo $giorno."-".$mese."-".$anno;
                                ?>
                            </span>
                            <span class="item">Data di partenza:
                                <?php
                                    $stringaData = $temp['dataPartenza'];
                                    $giorno = substr($stringaData, 8,2);       
                                    $mese = substr($stringaData,5,2 );
                                    $anno = substr($stringaData,0,4 );
                                    echo $giorno."-".$mese."-".$anno;
                                ?>
                            </span>
                        </div>

                        <div class="zonaPagamento">
                            <span class="item">Totale: <?php echo $prezzoTotale;?>&euro;</span>
                            <span class="item">Crediti disponibili: <?php echo $cliente['crediti'];?></span>
                            <span class="item">Utilizza crediti: <input type="number" name="creditiUsati" value="0"  id="textInput" autocomplete="off" /> </span>
                            <span class="hide"></span>
                            <br />
                            <hr />
                            <span class="item" id="totComplessivo">Totale complessivo: <?php echo $prezzoTotale;?>&euro;</span>
                        </div>    

                    </div>
                    <input type="hidden" name="idCamera" value="<?php echo $temp['idCamera'];?>" />
                    <input type="hidden" name="dataArrivo" value="<?php echo $temp['dataArrivo'];?>" />
                    <input type="hidden" name="dataPartenza" value="<?php echo $temp['dataPartenza'];?>" />
                <?php   
                    }
                    if(isset($datiAttivita)){
                ?>
                    <div class="zonaSuperiore">
                        
                        <div class="zonaDati">
                            <span class="item">Nome attivit&agrave; : <?php echo $datiAttivita['nome'];?></span>
                            <span class="item">Prezzo all'ora : <?php echo $datiAttivita['prezzoOrario'];?>&euro;</span>
                            <span class="item">Data:
                                <?php
                                    $stringaData = $temp['dataAttivita'];
                                    $giorno = substr($stringaData, 8,2);       
                                    $mese = substr($stringaData,5,2 );
                                    $anno = substr($stringaData,0,4 );
                                    echo $giorno."-".$mese."-".$anno;
                                ?>
                            </span>
                            <span class="item">Orario inizio : <?php echo $temp['oraInizio']?></span>
                            <span class="item">Orario fine  : <?php echo $temp['oraFine']?></span>

                        </div>

                        <div class="zonaPagamento">
                            <span class="item">Totale: <?php echo $prezzoTotale;?>&euro;</span>
                            <span class="item">Crediti disponibili: <?php echo $cliente['crediti'];?></span>
                            <span class="item">Utilizza crediti: <input type="number" name="creditiUsati" value="0"  id="textInput" autocomplete="off" /> </span>
                            <span class="hide"></span>
                            <br />
                            <hr />
                            <span class="item" id="totComplessivo">Totale complessivo: <?php echo $prezzoTotale;?>&euro;</span>
                        </div>    

                    </div>
                    <input type="hidden" name="idAttivita" value="<?php echo $temp['idAttivita'];?>" />
                    <input type="hidden" name="dataAttivita" value="<?php echo $temp['dataAttivita'];?>" />
                    <input type="hidden" name="oraInizio" value="<?php echo $temp['oraInizio'];?>" />
                    <input type="hidden" name="oraFine" value="<?php echo $temp['oraFine'];?>" />
                    <input type="hidden" name="prezzoTotale" value="<?php echo $prezzoTotale?>" />


                    <?php 
                    }
                    ?>


                    <div class="zonaBottoni">
                        <input type="submit" class="button" name="ANNULLA" value="ANNULLA" />
                        <input type="submit" class="button" name="CONFERMA" value="CONFERMA" />
                    </div>
                </div>
            </form>
        </div>

        <script type="text/javascript">
            var maxCrediti = <?php echo json_encode($cliente['crediti']); ?>;
            var maxValue = <?php echo json_encode($prezzoTotale); ?>;
            $("#textInput").on('change' , function(e){
                if(e.target.value < 0){
                    e.target.value = 0;
                }

                if(e.target.value > maxCrediti){
                    e.target.value = maxCrediti; 
                }
                
                var prezzoDaSottrarre = e.target.value / 5;

                var totComplessivo = maxValue - prezzoDaSottrarre;
                var encodedStr = "Totale complessivo: " + totComplessivo + "&euro;";
                var decoded = $("<div/>").html(encodedStr).text();
                $("#totComplessivo").text(decoded);
            });
        </script>

    </body>
</html>