<?php
require_once('funzioniGetPHP.php');
require_once('funzioniModificaPHP.php');
require_once('funzioniPHP.php');
session_start();

if(!isset($_SESSION['codFiscUtenteLoggato'])){
    if(!isset($_SESSION['loginType'])){
        header('Location: intro.php');
    }
}
else{
    header('Location: areaUtente.php');
}
if(!isset($_SESSION['idPrenotazioneAttivita']) && !isset($_POST['idPrenotazioneAttivita'])){
    header('Location: listaPrenotazioniAttivita.php');
    exit();
}


$error="False";
    if(isset($_POST['annulla']) || isset($_POST['cambia'])){
        if(isset($_POST['annulla'])){
            unset($_SESSION['idPrenotazioneAttivita']);
            header('Location: listaPrenotazioniAttivita.php');
            exit();
        }
        else{
            if($_POST['data']!="" || $_POST['oraInizio']!="" || $_POST['oraFine']!=""){
                modificaPrenotazioneAttivita($_POST['idPrenotazioneAttivita'],$_POST['data'],$_POST['oraInizio'],$_POST['oraFine']);
                unset($_SESSION['idPrenotazioneAttivita']);
                header('Location: listaPrenotazioniAttivita.php');
                exit();
            }else{
                $idPrenotazioneAttivita=$_POST['idPrenotazioneAttivita'];
                $error="True";
            }
        
        }
    }else{
        if(isset($_SESSION['idPrenotazioneAttivita'])){
                $idPrenotazioneAttivita=$_SESSION['idPrenotazioneAttivita'];
                $idAttivita=substr($_SESSION['idPrenotazioneAttivita'],0,2);
                $prenotazione=getPrenotazioneAttivita($_SESSION['idPrenotazioneAttivita']);
                unset($_SESSION['idPrenotazioneAttivita']);
        }else{
            $idAttivita=substr($idPrenotazioneAttivita,0,2);
            $prenotazione=getPrenotazioneAttivita($idPrenotazioneAttivita);
        }
                
                $attivita=getDatiAttivita($idAttivita);
                $temp=getSoggiornoAttivo($prenotazione['codFisc']);
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

                $oraMin=$attivita['oraApertura'];
                $oraMax=$attivita['oraChiusura'];
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
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
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

                <div class="containerColumn">

                    <p><strong>Inserisci una nuova data:</strong></p>
                    <input type="date" class="textInput" name="data">

                </div>
        </div>

        <div class="riga">
            <div class="containerColumn">

                <p><strong>Inserisci l'orario di inizio attivit&agrave;:</strong></p>
        
                <input type="time" name="oraInizio" class="oraInizio" />                        
                    
            </div>
        </div>

        <div class="riga">
            <div class="containerColumn">

                <p><strong>Inserisci l'orario di fine attivit&agrave;:</strong></p>
        
                <input type="time" name="oraFine" class="oraFine" />                      
                    
            </div>
        </div>

        <div class="spaceBetween">
        <input type="submit" class="buttonSx" value="Annulla" name="annulla" />
        <input type="submit" class="buttonDx" value="Cambia" name="cambia" />
        <input type="hidden" name="idPrenotazioneAttivita" value="<?php echo $idPrenotazioneAttivita?>">
        </div>
        </form>

        <?php
        if ($error=="True"){
            echo "
                <div class\"riga\">
                <p class=\"errorLabel\">Cambiare almeno un dato!</p>
                </div>
            ";
            }
    ?>

        </form>
    </div>

    <script>

//Perfezionare la parte sottostante
            
            // $(".data").attr("autocomplete" , "off");      //Rimuove l'autocompletamento (cioè non vengono suggerite delle date)
            // $(".data").datepicker({
            //     dateFormat: 'dd-mm-yy',         
            //     minDate: dataInizio,             //Con questo attributo imposto che la data minima selezionabile è la data odierna
            //     maxDate: dataFine
            // });

            

            // $(".oraInizio").attr("autocomplete" , "off");
            // $(".oraFine").attr("autocomplete" , "off");

            // $('.oraInizio').timepicker({
            //     timeFormat: 'HH:mm',
            //     interval: 60,
            //     minTime: oraInizioAttivita,
            //     maxTime: oraFineAttivita,
            //     dynamic: false,
            //     dropdown: true,
            //     scrollbar: true
            // });
            // $('.oraFine').timepicker({
            //     timeFormat: 'HH:mm',
            //     interval: 60,
            //     minTime: oraInizioAttivita,
            //     maxTime: oraFineAttivita,
            //     dynamic: false,
            //     dropdown: true,
            //     scrollbar: true
            // });

        </script>
</body>
</html>