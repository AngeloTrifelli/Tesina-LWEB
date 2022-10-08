<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniPHP.php');

    $patternDate = "/^[0-9]{2}-[0-9]{2}-[0-9]{4}$/";
    $patternOrario = "/^[0-9]{2}:[0-9]{2}$/";

    session_start();
    if(isset($_SESSION['codFiscUtenteLoggato'])){
        $temp = $_SESSION['soggiornoAttivo'];
        if($temp != "null"){
            if($temp['statoSoggiorno'] != "Approvato"){
                header('Location: areaUtente.php');
                exit();
            }
            else{
                $todayDate = date("Y-m-d");

                if($todayDate > $temp['dataArrivo']){
                    $stringaData = $todayDate;
                }
                else{
                    $stringaData = $temp['dataArrivo'];
                }
            
                $giorno = substr($stringaData, 8,2);       
                $mese = substr($stringaData,5,2 );
                $anno = substr($stringaData,0,4 );
                $dataMin= $giorno."-".$mese."-".$anno;

                $stringaData = $temp['dataPartenza'];
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

                $result = "";
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




    if(isset($_POST['ANNULLA']) || isset($_POST['CONFERMA'])){
        if(isset($_POST['ANNULLA'])){
            header('Location: homeRistorante.php');
            exit();
        }
        else{
            if(preg_match($patternDate , $_POST['dataPrenotazione']) && isset($_POST['locazione']) && isset($_POST['pasto']) && preg_match($patternOrario , $_POST['oraPrenotazione'])   ){                
                $stringaData = $_POST['dataPrenotazione'];
                $anno = substr($stringaData, 6 , 4);
                $mese = substr($stringaData, 3 , 2);
                $giorno = substr($stringaData , 0 , 2 );
                $dataPrenotazione= $anno."-".$mese."-".$giorno;

                $oraPrenotazione = $_POST['oraPrenotazione'].":00";

                $result = cercaTavoloDisponibile($dataPrenotazione , $_POST['locazione'] , $_POST['pasto'] , $oraPrenotazione , $_SESSION['codFiscUtenteLoggato']  );

                if($result == "success"){
                    header('Location: registrazioneCompletata.php');
                    exit();
                }                             
            }
            else{
                if($_POST['dataPrenotazione'] != "" &&  !preg_match($patternDate , $_POST['dataPrenotazione'])){
                    $erroreDate = "True";
                }

                if(isset($_POST['pasto']) && !preg_match($patternOrario , $_POST['oraPrenotazione'])){
                    $erroreOrario = "True";
                }
            }
                
        }
    }






    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Prenota un tavolo</title>

        <style type="text/css">
            <?php include "../CSS/prenotaTavolo.css" ?>
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
        <h1>PRENOTA UN TAVOLO:</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >

                <div class="mainArea">
                    <div class="zonaSuperiore">
                        
                        <div class="zonaSx">
                            <span class="item">Inserisci la data di prenotazione:</span>                            
                        <?php
                            if(isset($_POST['dataPrenotazione'])){
                                echo "<input type=\"text\" name=\"dataPrenotazione\" class=\"dateInput dataPrenotazione\" value=\"{$_POST['dataPrenotazione']}\"/>";
                            }
                            else{
                                echo '<input type="text" name="dataPrenotazione" class="dateInput dataPrenotazione"/>';
                            }

                            if(isset($_POST['CONFERMA']) && $_POST['dataPrenotazione'] == ""){
                                echo '<p class="errorLabel">Inserire una data!</p>';
                            }

                            if(isset($_POST['CONFERMA']) && isset($erroreDate)){
                                echo '<p class="errorLabel">La data inserita non è valida!</p>';
                            }
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
                        ?>
                        </div>

                        <div class="zonaDx">
                            <span class="item marginTop">Selezionare un pasto:</span>
                            <span>
                                <input type="radio" id="Pranzo" name="pasto" value="Pranzo"  />
                                <label for="Pranzo">Pranzo</label><br /> 
                            </span>                        
                            <span>
                                <input type="radio" id="Cena" name="pasto" value="Cena" />
                                <label for="Cena">Cena</label>
                            </span>         
                        <?php
                            if(isset($_POST['CONFERMA']) && !isset($_POST['pasto'])){
                                echo '<p class="errorLabel">Scegliere un pasto!</p>';
                            }
                        ?>
                            <span class="item marginTop">Inserisci l'orario di prenotazione:</span>
                            <input name="oraPrenotazione" class="textInput oraPrenotazione" /> 
                        <?php
                            if(isset($_POST['CONFERMA']) && isset($erroreOrario)){
                                echo '<p class="errorLabel">L\'orario inserito non è valido!</p>';
                            }
                        ?>                                              
                        </div>    

                    </div>

                    <?php
                        if(isset($_POST['CONFERMA']) && $result == "insuccess"){
                            echo '<p class="errorLabel">Non sono stati trovati tavoli disponibili!</p>';
                        }
                    ?>
                    <div class="zonaBottoni">
                        <input type="submit" class="button" name="ANNULLA" value="ANNULLA" />
                        <input type="submit" class="button" name="CONFERMA" value="CONFERMA" />
                    </div>
                </div>
            </form>
        </div>

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
        
           

        </script>
    </body>
</html>