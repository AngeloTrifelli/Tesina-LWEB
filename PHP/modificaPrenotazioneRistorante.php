<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniPHP.php');
    require_once('funzioniModificaPHP.php');
    require_once('funzioniInsertPHP.php');
    require_once('funzioniDeletePHP.php');
    session_start();

    if(isset($_POST['ANNULLA']) || isset($_POST['CONFERMA'])){
        if(isset($_POST['ANNULLA'])){
            if(isset($_POST['modificaPrenotazioneTavolo'])){
                header('Location: prenotazioniRistorante.php');
                exit();
            }
            else{                
                $_SESSION['dettagliSC'] = $_POST['idPrenotazione'];
                header('Location: dettagliPrenotazioneSC.php');
                exit();
            }                
        }
        else{
            if(isset($_POST['modificaPrenotazioneTavolo'])){
                if($_POST['dataPrenotazione'] != "" && isset($_POST['locazione']) && isset($_POST['pasto']) && $_POST['oraPrenotazione'] != ""){
                    $stringaData = $_POST['dataPrenotazione'];
                    $anno = substr($stringaData, 6 , 4);
                    $mese = substr($stringaData, 3 , 2);
                    $giorno = substr($stringaData , 0 , 2 );
                    $dataPrenotazione= $anno."-".$mese."-".$giorno;
    
                    $oraPrenotazione = $_POST['oraPrenotazione'].":00";
    
                    $result = modificaPrenotazioneTavolo($_POST['idPrenotazione'], $dataPrenotazione , $_POST['locazione'] , $_POST['pasto'] , $oraPrenotazione);
    
                    if($result == "success"){
                        header('Location: prenotazioniRistorante.php');
                        exit();
                    }
                    else{
                        $arrayDati['idPrenotazione'] = $_POST['idPrenotazione']; 
                        $_SESSION['prenotazioneDaModificare'] = $arrayDati;
                    }
                }
                else{
                    $arrayDati['idPrenotazione'] = $_POST['idPrenotazione']; 
                    $_SESSION['prenotazioneDaModificare'] = $arrayDati;                        
                }
            }
            elseif(isset($_POST['aggiungiPortata'])){
                if(isset($_POST['selezionaPortata'])){
                    $portataSelezionata = $_POST['selezionaPortata'];
                    $quantitaSelezionata = $_POST['quantita'];
                    aggiungiPortataPrenotazioneSC($_POST['idPrenotazione'] , $portataSelezionata , $quantitaSelezionata);
                    $_SESSION['dettagliSC'] = $_POST['idPrenotazione'];
                    header('Location: dettagliPrenotazioneSC.php');
                    exit();   
                }
                else{
                    $arrayDati['idPrenotazione'] = $_POST['idPrenotazione'];
                    $arrayDati['tipoModifica'] = "aggiungiPortata";
                    $_SESSION['prenotazioneDaModificare'] = $arrayDati;
                    $portataMancante = "True";
                }
            }
            elseif(isset($_POST['eliminaPortata'])){
                if(isset($_POST['selezionaPortata'])){
                    $portataSelezionata = $_POST['selezionaPortata'];
                    $quantitaSelezionata = $_POST['quantita'];
                    rimuoviPortataPrenotazioneSC($_POST['idPrenotazione'], $portataSelezionata , $quantitaSelezionata);
                    $_SESSION['dettagliSC'] = $_POST['idPrenotazione'];
                    header('Location: dettagliPrenotazioneSC.php');
                    exit();
                }
                else{
                    $arrayDati['idPrenotazione'] = $_POST['idPrenotazione'];
                    $arrayDati['tipoModifica'] = "eliminaPortata";
                    $_SESSION['prenotazioneDaModificare'] = $arrayDati;
                    $portataMancante = "True";
                }
            }
            elseif(isset($_POST['modificaDataOra'])){
                if($_POST['dataPrenotazione'] != "" || isset($_POST['pasto']) ){
                    if($_POST['dataPrenotazione'] != ""){
                        $stringaData = $_POST['dataPrenotazione'];
                        $anno = substr($stringaData, 6 , 4);
                        $mese = substr($stringaData, 3 , 2);
                        $giorno = substr($stringaData , 0 , 2 );
                        $dataPrenotazione= $anno."-".$mese."-".$giorno;
                    }
                    else{
                        $dataPrenotazione = "";
                    }

                    if(isset($_POST['pasto'])){
                        modificaDataOraPrenotazioneSC($_POST['idPrenotazione'] , $dataPrenotazione , $_POST['oraPrenotazione']);
                    }
                    else{
                        modificaDataOraPrenotazioneSC($_POST['idPrenotazione'] , $dataPrenotazione , "");
                    }
                    $_SESSION['dettagliSC'] = $_POST['idPrenotazione'];
                    header('Location: dettagliPrenotazioneSC.php');
                    exit();                                        
                }
                else{
                    $arrayDati['idPrenotazione'] = $_POST['idPrenotazione'];
                    $arrayDati['tipoModifica'] = "dataOra";
                    $_SESSION['prenotazioneDaModificare'] = $arrayDati;
                    $datiMancanti = "True";
                }
            }
        }
    }


    if(isset($_SESSION['prenotazioneDaModificare'])){
        $temp = $_SESSION['prenotazioneDaModificare'];
        $idPrenotazione = $temp['idPrenotazione'];        
        if(substr($idPrenotazione, 0 , 3) == "PSC"){            
            if($temp['tipoModifica'] == "aggiungiPortata"){
                $portate = getPortate();                
                $aggiungiPortata = "True";
            }
            elseif($temp['tipoModifica'] == "eliminaPortata"){
                $datiPrenotazione = getPrenotazioneServizioCamera($idPrenotazione);
                $eliminaPortata = "True";
            }
            else{
                $datiPrenotazione = getPrenotazioneServizioCamera($idPrenotazione);
                $codFiscCliente = $datiPrenotazione['codFiscCliente'];
                $datiSoggiorno = getSoggiornoAttivo($codFiscCliente);

                $todayDate = date("Y-m-d");

                if($todayDate > $datiSoggiorno['dataArrivo']){
                    $stringaData = $todayDate;
                }
                else{
                    $stringaData = $datiSoggiorno['dataArrivo'];
                }

                $giorno = substr($stringaData, 8,2);       
                $mese = substr($stringaData,5,2 );
                $anno = substr($stringaData,0,4 );
                $dataMin= $giorno."-".$mese."-".$anno;

                $stringaData = $datiSoggiorno['dataPartenza'];
                $giorno = substr($stringaData, 8,2);       
                $mese = substr($stringaData,5,2 );
                $anno = substr($stringaData,0,4 );
                $dataMax= $giorno."-".$mese."-".$anno;

                $orari = getOrariRistorante();
                $hour = new DateTime('00:30:00');

                $temp2 = new DateTime($orari['chiusuraPranzo']);            
                $nuovaOraFinePranzo = differenzaOrari($temp2 , $hour );

                $temp2 = new DateTime($orari['chiusuraCena']);                
                $nuovaOraFineCena = differenzaOrari($temp2 , $hour );

                $modificaDataOra = "True";
            }
        }
        else{            
            $datiPrenotazione  = getPrenotazioneTavolo($idPrenotazione);
            $codFiscCliente = $datiPrenotazione['codFiscCliente'];
            $datiSoggiorno = getSoggiornoAttivo($codFiscCliente);

            $todayDate = date("Y-m-d");

            if($todayDate > $datiSoggiorno['dataArrivo']){
                $stringaData = $todayDate;
            }
            else{
                $stringaData = $datiSoggiorno['dataArrivo'];
            }

            $giorno = substr($stringaData, 8,2);       
            $mese = substr($stringaData,5,2 );
            $anno = substr($stringaData,0,4 );
            $dataMin= $giorno."-".$mese."-".$anno;

            $stringaData = $datiSoggiorno['dataPartenza'];
            $giorno = substr($stringaData, 8,2);       
            $mese = substr($stringaData,5,2 );
            $anno = substr($stringaData,0,4 );
            $dataMax= $giorno."-".$mese."-".$anno;

            $orari = getOrariRistorante();
            $hour = new DateTime('00:30:00');

            $temp2 = new DateTime($orari['chiusuraPranzo']);            
            $nuovaOraFinePranzo = differenzaOrari($temp2 , $hour );

            $temp2 = new DateTime($orari['chiusuraCena']);                
            $nuovaOraFineCena = differenzaOrari($temp2 , $hour );

            $modificaDataOra = "True";
            $modificaPrenotazioneTavolo = "True";
        }      

        unset($_SESSION['prenotazioneDaModificare']);    
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
        <title>Modifica prenotazione</title>

        <style type="text/css">
            <?php include "../CSS/modificaPrenotazioneRistorante.css" ?>
        </style>

        <link href="https://code.jquery.com/ui/1.13.2/themes/blitzer/jquery-ui.css" rel="stylesheet"/>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css" />

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&amp;display=swap" />
    </head>

    <body>

        <div class="containerCentrale">
        <?php
            if(isset($aggiungiPortata)){
                echo '<h1>AGGIUNGI PORTATA:</h1>';
            }
            elseif(isset($eliminaPortata)){
                echo '<h1>ELIMINA PORTATA:</h1>';
            }
            elseif(isset($modificaPrenotazioneTavolo)){
                echo '<h1>MODIFICA PRENOTAZIONE AL TAVOLO</h1>';
            }
            elseif(isset($modificaDataOra)){
                echo '<h1>MODIFICA DATA/ORA:</h1>';
            }
        ?>        
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >

                <div class="mainArea">
                    <div class="zonaSuperiore">
                        
                        <div class="zonaSx">
                        <?php
                            if(isset($aggiungiPortata)){
                                echo '<span class="item">Scegli la portata da aggiungere:</span>';                                                            
                                echo '<select class="selectInput" name="selezionaPortata">
                                      <option disabled selected value="Scegli">-- Scegli -- </option>';
                                
                                for($i=0 ; $i < 4 ; $i++){
                                    $tipoPortata = $portate[$i];
                                    for($j=0 ; $j < count($tipoPortata) ; $j++){
                                        $portata = $tipoPortata[$j];
                                        $nomePortata = $portata['descrizione'];                                        

                                        echo "<option value=\"{$nomePortata}\">{$nomePortata}</option>";
                                    }
                                }
                                echo '</select>';
                            }
                            elseif(isset($eliminaPortata)){
                                echo '<span class="item">Scegli la portata:</span>';
                                echo '<select class="selectInput" name="selezionaPortata">
                                      <option disabled selected value="Scegli">-- Scegli -- </option>';

                                $portateScelte = $datiPrenotazione['portateScelte'];
                                for($i=0; $i < count($portateScelte) ; $i++ ){
                                    $portataScelta = $portateScelte[$i];
                                    $nomePortata = $portataScelta['descrizione'];

                                    echo "<option value=\"{$nomePortata}\">{$nomePortata}</option>";
                                }    
                                      
                                echo '</select>';                                
                            }
                            elseif(isset($modificaDataOra)){
                                echo '<span class="item">Inserire la data di prenotazione:</span>';
                                if(isset($_POST['dataPrenotazione'])){
                                    echo "<input type=\"text\" name=\"dataPrenotazione\" class=\"dateInput dataPrenotazione\" value=\"{$_POST['dataPrenotazione']}\"/>";
                                }
                                else{
                                    echo '<input type="text" name="dataPrenotazione" class="dateInput dataPrenotazione"/>';
                                }
    
                                if(isset($_POST['CONFERMA']) && $_POST['dataPrenotazione'] == "" && isset($modificaPrenotazioneTavolo)){
                                    echo '<p class="errorLabel">Inserire una data!</p>';
                                }
                                if(isset($modificaPrenotazioneTavolo)){
                            ?>
                                    <h2>Locazione:</h2>
                                    <span>                                           
                                        <input type="radio" id="Interna" name="locazione" value="Interna" <?php if(isset($_POST['locazione'])){if($_POST['locazione']=="Interna"){echo 'checked';}} ?> />                            
                                        <label for="Interna">Interna</label><br />                         
                                    </span>
                                    <span>
                                        <input type="radio" id="Esterna" name="locazione" value="Esterna"  <?php if(isset($_POST['locazione'])){if($_POST['locazione']=="Esterna"){echo 'checked';}} ?> />
                                        <label for="Esterna">Esterna</label>
                                    </span>
                            <?php
                                    if(isset($_POST['CONFERMA']) && !isset($_POST['locazione'])){
                                        echo '<p class="errorLabel">Scegliere la locazione!</p>';
                                    }
                                }
                            }                        
                        ?>                            
                        </div>

                        <div class="zonaDx">
                        <?php
                            if(isset($aggiungiPortata) || isset($eliminaPortata)){
                                if(isset($aggiungiPortata)){
                                    echo '<span class="item">Quantita da aggiungere:</span>';
                                    echo '<input type="number" class="numberInput" name="quantita" />';
                                }
                                else{
                                    echo '<span class="item">Quantita scelta dal cliente:</span>';
                                    echo '<span class="item" id="quantitaScelta">--</span>';
                                    echo '<span class="item">Quantita da rimuovere:</span>';
                                    echo '<input type="number" class="numberInput" name="quantita" />';
                                }                                
                            }
                            elseif(isset($modificaDataOra)){
                                echo '
                                <span class="item">Selezionare un pasto:</span>
                                <span>
                                    <input type="radio" id="Pranzo" name="pasto" value="Pranzo"  />
                                    <label for="Pranzo">Pranzo</label><br /> 
                                </span>                        
                                <span>
                                    <input type="radio" id="Cena" name="pasto" value="Cena" />
                                    <label for="Cena">Cena</label>
                                </span>';
                                if(isset($_POST['CONFERMA']) && !isset($_POST['pasto']) && isset($modificaPrenotazioneTavolo)){
                                    echo '<p class="errorLabel">Scegliere un pasto!</p>';
                                }
                                echo '
                                <span class="item">Inserisci l\'orario di prenotazione</span>
                                <input name="oraPrenotazione" class="textInput oraPrenotazione" />';
                            }
                        ?>                                   
                        </div>    

                    </div>
                <?php
                    if(isset($portataMancante)){
                        echo '<p class="errorLabel">Scegliere una portata!</p>';
                    }

                    if(isset($datiMancanti)){
                        echo '<p class="errorLabel">Dati mancanti!<br />Inserire almeno la data e/o l\'orario</p>';
                    }          
                    
                    if(isset($result) && $result == "insuccess"){
                        echo '<p class="errorLabel">Non sono stati trovati tavoli disponibili!</p>';
                    }
                ?>
                    
                    <div class="zonaBottoni">
                        <input type="submit" class="button" name="ANNULLA" value="ANNULLA" />
                        <input type="submit" class="button" name="CONFERMA" value="CONFERMA" />
                    </div>
                </div>

                <input type="hidden" name="idPrenotazione" value="<?php echo $idPrenotazione;?>" />
            <?php
                if(isset($aggiungiPortata)){
                    echo '<input type="hidden" name="aggiungiPortata" value="aggiungiPortata" />';
                }
                elseif(isset($eliminaPortata)){
                    echo '<input type="hidden" name="eliminaPortata" value="eliminaPortata" />';

                    $portateScelte = $datiPrenotazione['portateScelte'];
                    for($i=0; $i < count($portateScelte) ; $i++ ){
                        $portataScelta = $portateScelte[$i];
                        $nomePortata = $portataScelta['descrizione'];
                        $quantita = $portataScelta['quantita'];

                        $nomePortata = str_replace(" ", "", $nomePortata);
                        $nomeInputTypeHidden = $nomePortata."-quantita";

                        echo "<input type=\"hidden\" name=\"{$nomeInputTypeHidden}\" value=\"{$quantita}\" />";
                    } 

                }
                elseif(isset($modificaDataOra)){
                    echo '<input type="hidden" name="modificaDataOra" value="modificaDataOra" />';
                }

                if(isset($modificaPrenotazioneTavolo)){                    
                    echo '<input type="hidden" name="modificaPrenotazioneTavolo" value="modificaPrenotazioneTavolo" />';
                }
            ?>

            </form>
        </div>

    <?php
        if(isset($aggiungiPortata)){
    ?>
            <script>
                $(".numberInput").prop("disabled" , true);
                $(".numberInput").on("change", function(){
                    if($(".numberInput").val() < 1){
                        $(".numberInput").val(1);
                    }
                });

                $(".selectInput").on("change" , function(){
                    $(".numberInput").prop("disabled", false);
                    $(".numberInput").val(1);
                });
            </script>
    <?php
        }
        elseif(isset($eliminaPortata)){
    ?>
            <script>
                $(".numberInput").prop("disabled" , true);
                
                $(".selectInput").on("change" , function(){                    
                    var portataScelta = $(".selectInput").val();
                    portataScelta = portataScelta.replace(/\s+/g, '');                  
                    
                    var quantita = $("input[type=hidden][name="+portataScelta+"-quantita]").val();
                    $("#quantitaScelta").text(quantita);  
                    
                    $(".numberInput").prop("disabled", false);
                    $(".numberInput").val(1);
                    $(".numberInput").off();

                    $(".numberInput").on("change", function(){
                        if($(".numberInput").val() < 1){
                            $(".numberInput").val(1);
                        }

                        if($(".numberInput").val() > quantita){
                            $(".numberInput").val(quantita);
                        }
                    });
                });
            </script>
    <?php
        }
        elseif(isset($modificaDataOra)){
    ?>
            <script>
            var dataInizio=<?php echo json_encode($dataMin); ?>;
            var dataFine=<?php echo json_encode($dataMax); ?>;

            var oraInizioPranzo = <?php echo json_encode($orari['aperturaPranzo']); ?>; 
            var oraFinePranzo = <?php echo json_encode($nuovaOraFinePranzo); ?>;                    

            var oraInizioCena = <?php echo json_encode($orari['aperturaCena']); ?>; 
            var oraFineCena = <?php echo json_encode($nuovaOraFineCena); ?>;   
          

    
            $(".oraPrenotazione").prop("disabled", true);
            $(".oraPrenotazione").attr("autocomplete" , "off");

            $('input[type=radio][name=pasto]').change(function() {
                if(this.value == 'Pranzo'){                    
                    $(".oraPrenotazione").prop("disabled", false);
                    $(".oraPrenotazione").off();
                    $(".oraPrenotazione").timepicker('destroy');
                    $('.oraPrenotazione').timepicker({
                        timeFormat: 'HH:mm',
                        dynamic: false,
                        dropdown: true,
                        scrollbar: true,
                        interval: 15,                   
                        minTime: oraInizioPranzo,
                        maxTime: oraFinePranzo,                                                   
                    });                  
                    $(".oraPrenotazione").val(oraInizioPranzo.substring(0,5));  
                    $(".oraPrenotazione").on("change", function(){                                           
                        var element = $(this).val();                        
                        if(element < oraInizioPranzo){
                            $(this).val(oraInizioPranzo.substring(0,5));
                        }
                        
                        if(element > oraFinePranzo){                                                                        
                            $(this).val(oraFinePranzo.substring(0,5));
                        }                                                
                    });
                }
                else if (this.value == 'Cena'){               
                    $(".oraPrenotazione").prop("disabled", false); 
                    $(".oraPrenotazione").off();
                    $(".oraPrenotazione").timepicker('destroy'); 
                    $('.oraPrenotazione').timepicker({
                        timeFormat: 'HH:mm',
                        dynamic: false,
                        dropdown: true,
                        scrollbar: true,
                        interval: 15,       
                        minTime: oraInizioCena,
                        maxTime: oraFineCena,        
                    });                    
                    $(".oraPrenotazione").val(oraInizioCena.substring(0,5));
                    $(".oraPrenotazione").on("change", function(){                    
                        var element = $(this).val();                        
                        if(element < oraInizioCena){
                            $(this).val(oraInizioCena.substring(0,5));
                        }
                        
                        if(element > oraFineCena){                                                        
                            $(this).val(oraFineCena.substring(0,5));
                        }                                                
                    });
                }
            });

            $(".dataPrenotazione").attr("autocomplete" , "off");

            $(".dataPrenotazione").datepicker({
                dateFormat: 'dd-mm-yy',         
                minDate: dataInizio,
                maxDate: dataFine
            });     
            $(".dataPrenotazione").on("change", function(){ 
                var annoInizio=dataInizio.substring(6,10);
                var meseInizio=dataInizio.substring(3,5);
                var giornoInizio=dataInizio.substring(0,2);
                var annoFine=dataFine.substring(6,10);
                var meseFine=dataFine.substring(3,5);
                var giornoFine=dataFine.substring(0,2);
                var element = $(this).val();
                var annoElement=element.substring(6,10);
                var meseElement=element.substring(3,5);
                var giornoElement=element.substring(0,2);
                        if((annoElement<annoInizio) || ((annoElement>=annoInizio) && (meseElement<meseInizio)) ||((annoElement>=annoInizio) && (meseElement>=meseInizio) && (giornoElement<giornoInizio))){
                            $(this).val(dataInizio);
                        }
                        if((annoElement>annoFine) || ((annoElement<=annoFine) && (meseElement>meseFine)) ||((annoElement<=annoFine) && (meseElement<=meseFine) && (giornoElement>giornoFine))){                                                                        
                            $(this).val(dataFine);
                        }                                                
                    });              
        </script>
    <?php
        }
    ?>




    </body>
</html>