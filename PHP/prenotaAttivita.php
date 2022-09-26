<?php
    echo '<?xml version="1.0" encoding="UTF-8?>';
    require_once('funzioniPHP.php');
    require_once('funzioniInsertPHP.php');
    require_once('funzioniGetPHP.php');

    session_start();
    $datiMancanti="";
    $orariScorretti="";

    if(isset($_SESSION['codFiscUtenteLoggato'])){
        $temp=$_SESSION['soggiornoAttivo'];
        if($temp!="null"){
            if($temp['statoSoggiorno']!= "Approvato"){
                header("Location: areaUtente.php");
            }else{
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

                $attivita=getDatiAttivita($_SESSION['idAttivita']);
                $oraMin=$attivita['oraApertura'];
                $oraMax=$attivita['oraChiusura'];
                if(isset($_POST['prenota'])){

                    if ($_POST['data']!="" && $_POST['oraInizio']!=""  && $_POST['oraFine']!=""){
                        if($_POST['oraFine']>$_POST['oraInizio']){
            
                            $arrayAttivita['idAttivita']=$_SESSION['idAttivita'];
                            $stringaData = $_POST['data'];
                            $anno = substr($stringaData, 6 , 4);
                            $mese = substr($stringaData, 3 , 2);
                            $giorno = substr($stringaData , 0 , 2 );
                            $arrayAttivita['dataAttivita'] = $anno."-".$mese."-".$giorno;
            
                            $arrayAttivita['oraInizio']=$_POST['oraInizio'];
                            $arrayAttivita['oraFine']=$_POST['oraFine'];
                            $_SESSION['prenotazioneAttivita'] = $arrayAttivita;
                       
                            header('Location: confermaPrenotazione.php');
                        }else{
                            $orariScorretti="True";
                             }
            
                    }else{
                        $datiMancanti = "True";
                    }
            
                }
                }
            }else{
                header('Location: areaUtente.php');
                 }
    }
    else{
        header('Location: login.php');
        }   
             
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Prenota attività</title>

    <style>
        <?php include "../CSS/prenotaAttivita.css" ?>
    </style>

        <link href="https://code.jquery.com/ui/1.13.2/themes/blitzer/jquery-ui.css" rel="stylesheet"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>



    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&display=swap" />
</head>

<body>
    <div class="containerCentrale">

    <h1>PRENOTA ATTIVIT&Agrave;:</h1>



            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">

                

                    

                        <div class="containerData">

                            <p><strong>Inserisci la data:</strong></p>
                            <?php
                            if(isset($_POST['data'])){
                                echo "<input type=\"text\" name=\"data\" class=\"dateInput marginRight data\"  value=\"{$_POST['data']}\" />";
                            }
                            else{
                                echo  "<input type=\"text\" name=\"data\" class=\"dateInput marginRight data\"  />";
                            }
                            
                        ?>        
                        </div>                    


                    <div class="riga">

                        <div class="containerColumn">


                    <p><strong>Inserisci l'orario di inizio:</strong></p>
                    <?php 
                                        if(isset($_POST['oraInizio'])){
                                            echo "<input  name=\"oraInizio\" class=\"oraInizio\" value=\"{$_POST['oraInizio']}\" />";                        
                                        }
                                        else{
                                            echo "<input  name=\"oraInizio\" class=\"oraInizio\" />";                        
                                        }
                                       
                                    ?>
                        </div>
                        
                            <div class="containerColumn">

                    <p><strong>Inserisci l'orario di fine:</strong></p>
                    <?php 
                                         if(isset($_POST['oraFine'])){
                                            echo "<input  name=\"oraFine\" class=\"oraFine\" value=\"{$_POST['oraFine']}\" />";                        
                                        }
                                        else{
                                            echo "<input  name=\"oraFine\" class=\"oraFine\" />";                        
                                        }
                                        
                                    ?>
                            
                        </div>

                    </div>

                    <input type="submit" class="button" value="Prenota" name="prenota" />

                    <?php
                        if(isset($_POST['prenota']) && $datiMancanti == "True"){
                    ?>
                            <div class="row">
                                <h2 class="errorLabel">Dati mancanti!</h2>
                            </div>
                    <?php
                        }
                    ?>

                    <?php
                        if(isset($_POST['prenota']) && $orariScorretti == "True"){
                    ?>
                            <div class="row">
                                <h2 class="errorLabel">Inserire degli orari accettabili!</h2>
                            </div>
                    <?php
                        }
                    ?>

                </div>

            </form>
        
        </div>

    </div>

    <script>
            var dataInizio=<?php echo json_encode($dataMin); ?>;
            var dataFine=<?php echo json_encode($dataMax); ?>;
            $(".data").attr("autocomplete" , "off");      //Rimuove l'autocompletamento (cioè non vengono suggerite delle date)
            $(".data").datepicker({
                dateFormat: 'dd-mm-yy',         
                minDate: dataInizio,             //Con questo attributo imposto che la data minima selezionabile è la data odierna
                maxDate: dataFine
            });

            var oraInizio=<?php echo json_encode($oraMin); ?>;
            var oraFine=<?php echo json_encode($oraMax); ?>;
            
            $(".oraInizio").attr("autocomplete" , "off");  
            $('.oraInizio').timepicker({
                timeFormat: 'HH:mm',
                interval: 60,
                minTime: oraInizio,
                maxTime: oraFine,
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
            $(".oraFine").attr("autocomplete" , "off");  
            $('.oraFine').timepicker({
                timeFormat: 'HH:mm',
                interval: 60,
                minTime: oraInizio,
                maxTime: oraFine,
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
    </script>
</body>

</html>