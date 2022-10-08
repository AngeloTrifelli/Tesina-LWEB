<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniPHP.php');

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

    if(isset($_SESSION['datiPrenotazioneSC'])){
        $arrayDati = $_SESSION['datiPrenotazioneSC'];
        $dataPrenotazione = $arrayDati['dataPrenotazione'];
        $oraPrenotazione = $arrayDati['oraPrenotazione'];
        unset($_SESSION['datiPrenotazioneSC']);
    }
    else{
        if(isset($_POST['ANNULLA'])  || isset($_POST['CONTINUA'])){
            if(isset($_POST['ANNULLA'])){
                header('Location: homeRistorante.php');
                exit();
            }
            else{
                $portateScelte =  individuaPortateSelezionate();
                if($portateScelte != "null"){
                    $arrayDati['portateScelte'] = $portateScelte;
                    $arrayDati['dataPrenotazione'] = $_POST['dataPrenotazione'];
                    $arrayDati['oraPrenotazione'] = $_POST['oraPrenotazione'];
                    $arrayDati['richiesteAggiuntive'] = $_POST['note'];                    
                    $_SESSION['richiestaSC'] = $arrayDati;
                    $_SESSION['accessoPermesso'] = "True";
                    header('Location: dettagliPrenotazioneSC.php');
                    exit();
                }
                else{
                    $errore = "True";
                    $dataPrenotazione = $_POST['dataPrenotazione'];
                    $oraPrenotazione = $_POST['oraPrenotazione'];
                }
            }
        }
        else{
            header('Location: areaUtente.php');
            exit();
        }

    }

    

    $portate = getPortate();
    $antipasti = $portate[0];
    $primiPiatti = $portate[1];
    $secondiPiatti = $portate[2];
    $dolci = $portate[3];


    
    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Prenota servizio in camera: scegli portate</title>

        <style type="text/css">
            <?php include "../CSS/listaPortate.css" ?>
        </style>

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&amp;display=swap" />
    </head>

    <body>

        <div class="containerCentrale">
        <h1>SCEGLI PORTATE:</h1>
        <?php
            if(isset($errore)){
                echo '<p class="errorLabel alignCenter">Selezionare almeno una portata!</p>';
            }
        ?>
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
                    <?php
                        $numAntipasti = count($antipasti);
                        if($numAntipasti >= 1){
                            for($i=0 ; $i < $numAntipasti ; $i++){
                                $temp = $antipasti[$i];
                                $nomeInputTypeCheckBox = str_replace(" ", "", $temp['descrizione']);
                                $nomeInputTypeNumber = $nomeInputTypeCheckBox."-quantita";
                    ?>
                                <tr>
                                    <td class="content"><input type="checkbox" name="<?php echo $nomeInputTypeCheckBox;?>" value="<?php echo $temp['descrizione'];?>" /> <?php echo $temp['descrizione'];?></td>
                                    <td class="content alignCenter"><?php echo $temp['prezzo'];?> &euro;</td>
                                    <td class="content alignCenter"><input type="number" class="textInput" name="<?php echo $nomeInputTypeNumber;?>" /></td>
                                </tr>
                    <?php
                            }
                        }
                        else{
                            echo '
                            <tr>
                                <td colspan="3">Non sono disponibili antipasti al momento ...</td> 
                            </tr>
                            ';
                        }
                    ?>                          
                        <tr>
                            <td><h2>PRIMI PIATTI:</h2></td>    
                        </tr>                        
                    <?php
                        $numPrimiPiatti = count($primiPiatti);
                        if($numPrimiPiatti >= 1){
                            for($i=0 ; $i < $numPrimiPiatti ; $i++){
                                $temp = $primiPiatti[$i];
                                $nomeInputTypeCheckBox = str_replace(" ", "", $temp['descrizione']);
                                $nomeInputTypeNumber = $nomeInputTypeCheckBox."-quantita";
                    ?>
                                <tr>
                                    <td class="content"><input type="checkbox" name="<?php echo $nomeInputTypeCheckBox;?>" value="<?php echo $temp['descrizione'];?>" /> <?php echo $temp['descrizione'];?></td>
                                    <td class="content alignCenter"><?php echo $temp['prezzo'];?> &euro;</td>
                                    <td class="content alignCenter"><input type="number" class="textInput" name="<?php echo $nomeInputTypeNumber;?>" /></td>
                                </tr>
                    <?php
                            }
                        }
                        else{
                            echo '
                            <tr>
                                <td colspan="3">Non sono disponibili primi piatti al momento ...</td> 
                            </tr>
                            ';
                        }
                    ?>   
                        <tr>
                            <td><h2>SECONDI PIATTI:</h2></td>                           
                        </tr>
                    <?php
                        $numSecondiPiatti = count($secondiPiatti);
                        if($numSecondiPiatti >= 1){
                            for($i=0 ; $i < $numSecondiPiatti ; $i++){
                                $temp = $secondiPiatti[$i];
                                $nomeInputTypeCheckBox = str_replace(" ", "", $temp['descrizione']);
                                $nomeInputTypeNumber = $nomeInputTypeCheckBox."-quantita";
                    ?>
                                <tr>
                                    <td class="content"><input type="checkbox" name="<?php echo $nomeInputTypeCheckBox;?>" value="<?php echo $temp['descrizione'];?>" /> <?php echo $temp['descrizione'];?></td>
                                    <td class="content alignCenter"><?php echo $temp['prezzo'];?> &euro;</td>
                                    <td class="content alignCenter"><input type="number" class="textInput" name="<?php echo $nomeInputTypeNumber;?>" /></td>
                                </tr>
                    <?php
                            }
                        }
                        else{
                            echo '
                            <tr>
                                <td colspan="3">Non sono disponibili secondi piatti al momento ...</td> 
                            </tr>
                            ';
                        }
                    ?> 
                        <tr>
                            <td><h2>DOLCI:</h2></td>                            
                        </tr>
                    <?php
                        $numDolci = count($dolci);
                        if($numDolci >= 1){
                            for($i=0 ; $i < $numDolci ; $i++){
                                $temp = $dolci[$i];
                                $nomeInputTypeCheckBox = str_replace(" ", "", $temp['descrizione']);
                                $nomeInputTypeNumber = $nomeInputTypeCheckBox."-quantita";
                    ?>
                                <tr>
                                    <td class="content"><input type="checkbox" name="<?php echo $nomeInputTypeCheckBox;?>" value="<?php echo $temp['descrizione'];?>" /> <?php echo $temp['descrizione'];?></td>
                                    <td class="content alignCenter"><?php echo $temp['prezzo'];?> &euro;</td>
                                    <td class="content alignCenter"><input type="number" class="textInput" name="<?php echo $nomeInputTypeNumber;?>" /></td>
                                </tr>
                    <?php
                            }
                        }
                        else{
                            echo '
                            <tr>
                                <td colspan="3">Non sono disponibili dolci al momento ...</td> 
                            </tr>
                            ';
                        }
                    ?> 
                    </table> 
                   
                    <br />
                    <br />
                    <h2>Richieste aggiuntive:</h2>
                    <textarea type="text" class="textInput note" name="note"><?php if(isset($_POST['note'])){echo $_POST['note'];}?></textarea>                 

                    <div class="zonaBottoni">
                        <input type="submit" class="button" name="ANNULLA" value="ANNULLA" />
                        <input type="submit" class="button large" name="CONTINUA" value="CONTINUA" />
                    </div>
                </div>
                <?php
                    if(isset($dataPrenotazione)){
                ?>
                        <input type="hidden" name="dataPrenotazione" value="<?php echo $dataPrenotazione;?>" />
                        <input type="hidden" name="oraPrenotazione" value="<?php echo $oraPrenotazione;?>" />
                <?php
                    }
                ?>


            </form>
        </div>

        <script>
            var numAntipasti = <?php echo json_encode($numAntipasti); ?>; 
            var antipasti = <?php echo json_encode($antipasti); ?>;

            var numPrimiPiatti = <?php echo json_encode($numPrimiPiatti); ?>; 
            var primiPatti = <?php echo json_encode($primiPiatti); ?>; 

            var numSecondiPiatti = <?php echo json_encode($numSecondiPiatti); ?>; 
            var secondiPiatti = <?php echo json_encode($secondiPiatti); ?>; 

            var numDolci = <?php echo json_encode($numDolci); ?>; 
            var dolci = <?php echo json_encode($dolci); ?>; 
            
            var i;

            function generate_handler_number(nome){
                return function(){
                    if($('input[type=number][name='+nome+'-quantita]').val() < 1){
                        $('input[type=number][name='+nome+'-quantita]').val(1);
                    }
                }
            }


            function generate_handler_checkbox(nome){
                return function(){                    
                    if($('input[type=checkbox][name='+nome+']').is(':checked')){                           
                        $('input[type=number][name='+nome+'-quantita]').prop("disabled", false);
                        $('input[type=number][name='+nome+'-quantita]').val(1);
                    }
                    else{
                        $('input[type=number][name='+nome+'-quantita]').prop("disabled", true);
                        $('input[type=number][name='+nome+'-quantita]').val("");
                    }
                }
            }

            for(i=0; i < numAntipasti ; i++){
                var antipasto = antipasti[i];
                var nomePortata = antipasto.descrizione;
                var nomePortata = nomePortata.replace(/\s+/g, '');      //Rimuovo gli spazi dal nome della portata 

                $('input[type=number][name='+nomePortata+'-quantita]').prop("disabled", true);
                $('input[type=number][name='+nomePortata+'-quantita]').on("change", generate_handler_number(nomePortata));

                $('input[type=checkbox][name='+nomePortata+']').on("change", generate_handler_checkbox(nomePortata) );                
            }

            for(i=0; i < numPrimiPiatti ; i++){
                var primoPiatto = primiPatti[i];
                var nomePortata = primoPiatto.descrizione;
                var nomePortata = nomePortata.replace(/\s+/g, '');      

                $('input[type=number][name='+nomePortata+'-quantita]').prop("disabled", true);
                $('input[type=number][name='+nomePortata+'-quantita]').on("change", generate_handler_number(nomePortata));

                $('input[type=checkbox][name='+nomePortata+']').on("change", generate_handler_checkbox(nomePortata) );                
            }

            for(i=0; i < numSecondiPiatti ; i++){
                var secondoPiatto = secondiPiatti[i];
                var nomePortata = secondoPiatto.descrizione;
                var nomePortata = nomePortata.replace(/\s+/g, '');     

                $('input[type=number][name='+nomePortata+'-quantita]').prop("disabled", true);
                $('input[type=number][name='+nomePortata+'-quantita]').on("change", generate_handler_number(nomePortata));

                $('input[type=checkbox][name='+nomePortata+']').on("change", generate_handler_checkbox(nomePortata) );                
            }

            for(i=0; i < numDolci ; i++){
                var dolce = dolci[i];
                var nomePortata = dolce.descrizione;
                var nomePortata = nomePortata.replace(/\s+/g, '');      

                $('input[type=number][name='+nomePortata+'-quantita]').prop("disabled", true);
                $('input[type=number][name='+nomePortata+'-quantita]').on("change", generate_handler_number(nomePortata));

                $('input[type=checkbox][name='+nomePortata+']').on("change", generate_handler_checkbox(nomePortata) );                
            }                                        
           

        </script>


    </body>
</html>