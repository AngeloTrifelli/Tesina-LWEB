<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniModificaPHP.php');
    require_once('funzioniPHP.php');
    session_start();

    $patternDate = "/^[0-9]{2}-[0-9]{2}-[0-9]{4}$/";
    $patternOrario = "/^[0-9]{2}:[0-9]{2}$/";
    
    if(isset($_POST['annulla']) || isset($_POST['cambia'])){
        if(isset($_POST['annulla'])){
            unset($_SESSION['prenotazioneAttivitaDaModificare']);
            header('Location: listaPrenotazioniAttivita.php');
            exit();
        }
        else{
            if($_POST['tipoAzione'] == "modificaData"){
                if($_POST['data'] != ""){
                    if(preg_match($patternDate , $_POST['data'])){
                        $stringaData = $_POST['data'];
                        $anno = substr($stringaData, 6 , 4);
                        $mese = substr($stringaData, 3 , 2);
                        $giorno = substr($stringaData , 0 , 2 );
                        $dataPrenotazione= $anno."-".$mese."-".$giorno;

                        modificaPrenotazioneAttivita($_POST['idPrenotazioneAttivita'] , $dataPrenotazione , "" , "");
                        unset($_SESSION['prenotazioneAttivitaDaModificare']);
                        header('Location: listaPrenotazioniAttivita.php');
                        exit();
                    }
                    else{
                        $erroreDate = "True";
                    }
                }               
                else{
                    $datiMancanti = "True";
                }
            }
            elseif($_POST['tipoAzione'] == "modificaOraInizio"){
                if($_POST['oraInizio'] != ""){
                    if(preg_match($patternOrario , $_POST['oraInizio'])){
                        modificaPrenotazioneAttivita($_POST['idPrenotazioneAttivita'] , "" , $_POST['oraInizio'] , "");
                        unset($_SESSION['prenotazioneAttivitaDaModificare']);
                        header('Location: listaPrenotazioniAttivita.php');
                        exit();
                    }
                    else{
                        $erroreOraInizio = "True";
                    }
                }
                else{
                    $datiMancanti = "True";
                }
            }
            else{
                if($_POST['oraFine'] != ""){
                    if(preg_match($patternOrario , $_POST['oraFine'])){
                        modificaPrenotazioneAttivita($_POST['idPrenotazioneAttivita'] , "" , "", $_POST['oraFine']);
                        unset($_SESSION['prenotazioneAttivitaDaModificare']);
                        header('Location: listaPrenotazioniAttivita.php');
                        exit();
                    }
                    else{
                        $erroreOraFine = "True";
                    }
                }
                else{
                    $datiMancanti = "True";
                }
            }                   
        }
    }




    if(isset($_SESSION['prenotazioneAttivitaDaModificare']) && isset($_SESSION['loginType']) && $_SESSION['loginType'] != "Cliente"){
        $temp = $_SESSION['prenotazioneAttivitaDaModificare'];
        $idPrenotazioneAttivita = $temp['idPrenotazione'];
        $tipoAzione = $temp['tipoAzione'];
        $prenotazione = getPrenotazioneAttivita($idPrenotazioneAttivita);

        if($tipoAzione == "modificaData"){            
            $temp = getSoggiornoAttivo($prenotazione['codFisc']);

            $stringaData = $temp['dataArrivo'];
            $giorno = substr($stringaData, 8,2);       
            $mese = substr($stringaData,5,2 );
            $anno = substr($stringaData,0,4 );
            $dataMin= $giorno."-".$mese."-".$anno;

            $stringaData = $temp['dataPartenza'];
            $giorno = substr($stringaData, 8,2);       
            $mese = substr($stringaData,5,2 );
            $anno = substr($stringaData,0,4 );
            $dataMax= $giorno."-".$mese."-".$anno;            
        }
        else{
            $pieces = explode("-" , $idPrenotazioneAttivita);
            $idAttivita = $pieces[0];            
            $attivita = getDatiAttivita($idAttivita); 

            if($tipoAzione == "modificaOraInizio"){
                $oraMin = $attivita['oraApertura'];

                $hour = new DateTime('01:00:00');
                $temp2 = new DateTime($prenotazione['oraFine']);  

                $oraMaxInizio = differenzaOrari($temp2 , $hour );
            }
            else{
                $hour = substr($prenotazione['oraInizio'], 0 , 2);
                $newHour = $hour + 1;
                if(strlen($newHour) == 1){
                    $newHour = "0".$newHour;
                }
                $oraMinFine = $newHour.":00:00";                
                $oraMax = $attivita['oraChiusura'];
            }
        }        
    }
    else{
        unset($_SESSION['idPrenotazioneAttivitaDaModificare']);
        header('Location: listaPrenotazioniAttivita.php');
        exit();
    }
                                        
            

    echo '<?xml version="1.0" encoding="UTF-8?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Modifica prenotazione attivita</title>

    <style>
        <?php include "../CSS/modificaPrenotazioneAttivita.css" ?>
    </style>

    <link href="https://code.jquery.com/ui/1.13.2/themes/blitzer/jquery-ui.css" rel="stylesheet"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>



    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&display=swap" />
</head>

<body>
    <div class="containerCentrale">

        <h1>MODIFICA DATI PRENOTAZIONE ATTIVITA:</h1>

        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">

        <div class="riga">
            <?php 
                if($tipoAzione == "modificaData"){
            ?>                                
                    <div class="containerColumn">
                        <p><strong>Inserisci una nuova data:</strong></p>                                
                        <input type="text" class="textInput" name="data" />                    
                    <?php
                        if(isset($_POST['cambia']) && isset($erroreDate)){
                            echo '<p class="errorLabel">La data inserita non è valida!</p>';
                        }
                    ?>
                    </div>
            <?php  
                }
                elseif($tipoAzione == "modificaOraInizio"){
            ?>
                    <div class="containerColumn">
                        <p><strong>Inserisci l'orario di inizio della prenotazione:</strong></p>        
                        <input type="text" class="textInput" name="oraInizio" /> 
                        <?php
                            if(isset($_POST['cambia']) && isset($erroreOraInizio)){
                                echo '<p class="errorLabel">L\'orario inserito non è valido!</p>';
                            }
                        ?>                                           
                    </div>
            <?php
                }
                else{
            ?>
                    <div class="containerColumn">
                        <p><strong>Inserisci l'orario di fine della prenotazione:</strong></p>        
                        <input type="text" class="textInput" name="oraFine"  />   
                        <?php
                            if(isset($_POST['cambia']) && isset($erroreOraFine)){
                                echo '<p class="errorLabel">L\'orario inserito non è valido!</p>';
                            }
                        ?>                                       
                    </div>
            <?php
                }
            ?>
        </div>

        <?php
            if(isset($_POST['cambia']) && isset($datiMancanti)){
                echo '
                <div class="riga">
                    <div class="containerColumn">
                        <p class="errorLabel">Dati mancanti!</p>
                    </div>                    
                </div>';
            }
        ?>
    
        <div class="spaceBetween">
            <input type="submit" class="buttonSx" value="Annulla" name="annulla" />
            <input type="submit" class="buttonDx" value="Cambia" name="cambia" />            
        </div>
        


        <input type="hidden" name="idPrenotazioneAttivita" value="<?php echo $idPrenotazioneAttivita?>" />
        <input type="hidden" name="tipoAzione" value="<?php echo $tipoAzione?>" />

        </form>
               
    </div>

    <?php
        if($tipoAzione == "modificaData"){
    ?>
            <script>
                var dataInizio=<?php echo json_encode($dataMin); ?>;
                var dataFine=<?php echo json_encode($dataMax); ?>;

                $('input[type=text][name=data]').attr("autocomplete" , "off");
                $('input[type=text][name=data]').datepicker({
                    dateFormat: 'dd-mm-yy',         
                    minDate: dataInizio,            
                    maxDate: dataFine
                });  
            </script>
    <?php    
        }
        elseif($tipoAzione == "modificaOraInizio"){
    ?>  
            <script>
                var oraInizioAttivita = <?php echo json_encode($oraMin); ?>;
                var oraInizioAttivitaMax = <?php echo json_encode($oraMaxInizio); ?>;

                $('input[type=text][name=oraInizio]').attr("autocomplete" , "off");
                $('input[type=text][name=oraInizio]').timepicker({
                    timeFormat: 'HH:mm',
                    dynamic: false,
                    dropdown: true,
                    scrollbar: true,
                    interval: 60,
                    minTime: oraInizioAttivita,
                    maxTime: oraInizioAttivitaMax,
                })

                $('input[type=text][name=oraInizio]').on("change" , function(){
                    var element = $(this).val();
                    if(element < oraInizioAttivita){
                        $(this).val(oraInizioAttivita.substring(0,5));
                    }

                    if(element > oraInizioAttivitaMax){
                        $(this).val(oraInizioAttivitaMax.substring(0,5));
                    }                    
                })

            </script>
    <?php
        }
        else{
    ?>
            <script>
                var oraMinFine = <?php echo json_encode($oraMinFine); ?>;
                var oraFineAttivita = <?php echo json_encode($oraMax); ?>;

                $('input[type=text][name=oraFine]').attr("autocomplete" , "off");

                $('input[type=text][name=oraFine]').timepicker({
                    timeFormat: 'HH:mm',
                    dynamic: false,
                    dropdown: true,
                    scrollbar: true,
                    interval: 60,
                    minTime: oraMinFine,
                    maxTime: oraFineAttivita,
                })

                $('input[type=text][name=oraFine]').on("change" , function(){
                    var element = $(this).val();
                    if(element < oraMinFine){
                        $(this).val(oraMinFine.substring(0,5));
                    }

                    if(element > oraFineAttivita){
                        $(this).val(oraFineAttivita.substring(0,5));
                    }                    
                })
            </script>
    <?php
        }
    ?>   
    </body>
</html>