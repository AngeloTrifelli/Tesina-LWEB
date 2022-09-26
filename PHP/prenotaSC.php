<?php
    require_once('funzioniGetPHP.php');
    require_once('funzioniPHP.php');

    session_start();
    if(isset($_SESSION['codFiscUtenteLoggato'])){
        $temp = $_SESSION['soggiornoAttivo'];
        if($temp != "null"){
            if($temp['statoSoggiorno'] != "Approvato"){
                header('Location: areaUtente.php');
            }
            else{
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

                $orari = getOrariRistorante();
                $hour = new DateTime('00:30:00');

                $temp2 = new DateTime($orari['chiusuraPranzo']);            
                $nuovaOraFinePranzo = differenzaOrari($temp2 , $hour );

                $temp2 = new DateTime($orari['chiusuraCena']);                
                $nuovaOraFineCena = differenzaOrari($temp2 , $hour );   
            }
        }
        else{
            header('Location: prenotaOra.php');
        }
    }
    else{
        header('Location: login.php');
    }
    
    if(isset($_POST['INDIETRO']) || isset($_POST['CONTINUA'])){
        if(isset($_POST['INDIETRO'])){
            header('Location: homeRistorante.php');
        }
        else{
            if($_POST['dataPrenotazione'] != "" && isset($_POST['pasto'])){
                $stringaData = $_POST['dataPrenotazione'];
                $anno = substr($stringaData, 6 , 4);
                $mese = substr($stringaData, 3 , 2);
                $giorno = substr($stringaData , 0 , 2 );
                $dataPrenotazione= $anno."-".$mese."-".$giorno;

                $arrayDati['dataPrenotazione'] = $dataPrenotazione;

                $oraPrenotazione = $_POST['oraPrenotazione'].":00";
                $arrayDati['oraPrenotazione'] = $oraPrenotazione;
                $_SESSION['datiPrenotazioneSC'] = $arrayDati;
                header('Location: listaPortate.php');
                
            }
        }
    }

    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Prenota servizio in camera</title>

        <style type="text/css">
            <?php include "../CSS/prenotaSC.css" ?>
        </style>

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/blitzer/jquery-ui.css"/>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&amp;display=swap" />
    </head>

    <body>

        <div class="containerCentrale">
        <h1>PRENOTA SERVIZIO IN CAMERA:</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >
                <div class="mainArea">
                    <div class="zonaSuperiore">
                        <span class="item">Inserisci la data di prenotazione:</span>
                    <?php
                        if(isset($_POST['dataPrenotazione'])){
                            echo "<input type=\"text\" name=\"dataPrenotazione\" class=\"dateInput dataPrenotazione\" value=\"{$_POST['dataPrenotazione']}\"/>";
                        }
                        else{
                            echo '<input type="text" name="dataPrenotazione" class="dateInput dataPrenotazione"/>';
                        }

                        if(isset($_POST['CONTINUA']) && $_POST['dataPrenotazione'] == ""){
                            echo '<p class="errorLabel">Inserire una data!</p>';
                        }
                    ?>
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
                            if(isset($_POST['CONTINUA']) && !isset($_POST['pasto'])){
                                echo '<p class="errorLabel">Scegliere un pasto!</p>';
                            }
                        ?>
                        <span class="item marginTop">Inserisci l'orario di prenotazione:</span>
                        <input name="oraPrenotazione" class="textInput oraPrenotazione" />                          
                    </div>

                    

                    <div class="zonaBottoni">
                        <input type="submit" class="button" name="INDIETRO" value="TORNA INDIETRO" />
                        <input type="submit" class="button large" name="CONTINUA" value="CONTINUA" />
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


            $(".dataPrenotazione").attr("autocomplete" , "off");

            $(".dataPrenotazione").datepicker({
                dateFormat: 'dd-mm-yy',         
                minDate: dataInizio,
                maxDate: dataFine
            });


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
                        startTime: oraInizioPranzo,
                        minTime: oraInizioPranzo,
                        maxTime: oraFinePranzo,                                                   
                    });                  
                    $(".oraPrenotazione").val(oraInizioPranzo.substring(0,5));  
                    $(".oraPrenotazione").on("change", function(){                                           
                        var element = $(this).val();             
                        if(JSON.stringify(element).length > 5){
                            $(this).val(element.substring(0,5));
                        }
                        
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
                        if(JSON.stringify(element).length > 5){
                            $(this).val(element.substring(0,5));
                        }
                        
                        if(element < oraInizioCena){
                            $(this).val(oraInizioCena.substring(0,5));
                        }
                        
                        if(element > oraFineCena){                                                        
                            $(this).val(oraFineCena.substring(0,5));
                        } 
                        
                        
                    });
                }
            });

        </script>


    </body>
</html>