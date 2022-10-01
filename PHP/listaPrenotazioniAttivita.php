<?php
require_once('funzioniGetPHP.php');
require_once('funzioniPHP.php');
require_once('funzioniDeletePHP.php');

    session_start();
    if(!isset($_SESSION['codFiscUtenteLoggato'])){
        if(!isset($_SESSION['loginType'])){
            header('Location: intro.php');
        }
    }
    else{
        header('Location: areaUtente.php');
    }

    if(isset($_POST['bottonePremuto'])){
        $idPrenotazione = individuaBottoneidPrenotazioneAttivita();
        if($_POST[$idPrenotazione]=="ANNULLA"){
           
            if(isset($_COOKIE['Cancella'])){
                unset($_COOKIE['Cancella']);
                setcookie('Cancella', '', time() - 3600, '/');
                rimuoviPrenotazioneAttivita($idPrenotazione);
            }  
            
        }else{
            $_SESSION['idPrenotazioneAttivita']=$idPrenotazione;
            header('Location: modificaPrenotazioneAttivita.php');
            exit();
        }

    }
    

    $tabellaPrenotazioniAttivita=getPrenotazioniAttivitaAttive();
    $arrayPrenotazioniAttivitaAttive=$tabellaPrenotazioniAttivita[0];
    $arrayPrenotazioniAttivitaPassate=$tabellaPrenotazioniAttivita[1];

    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Lista prenotazioni attivit&agrave;</title>

        <style>
            <?php include "../CSS/listaPrenotazioniAttivita.css" ?>
        </style>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    </head>


    <body>
        <div class="top">

            <div class="topLeft">               
                <a href="./prenotazioniClienti.php">TORNA ALLE PRENOTAZIONI CLIENTI</a>  
            </div>

            <h1 class="alignCenter">LISTA PRENOTAZIONI ATTIVIT&Agrave;</h1>
           
            <div style="width: 18.5%;"></div>
               
        </div>
        <?php
        if(count($arrayPrenotazioniAttivitaAttive)>0){
            echo "<h3 class=\"titoloImportante alignCenter\">PRENOTAZIONI ATTIVE:</h3>";
        }else{
            echo "<h3 class=\"titoloImportante alignCenter\">NON CI SONO ATTIVIT&agrave; PRENOTATE ATTIVE</h3>";
            echo "<span class=\"trePuntini\">...</span>";
            }
        ?>

    
        <div class="mainContainer">
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"  >
        <?php
            for($i=0;$i<count($arrayPrenotazioniAttivitaAttive);$i++){
                $prenotazione=$arrayPrenotazioniAttivitaAttive[$i];
                $oraInizioAttivita=substr($prenotazione['oraInizio'],0,5);
                $oraFineAttivita=substr($prenotazione['oraFine'],0,5);
        ?>

                <table class="alignCenter tabella"  align="center">
                    <tr>
                        <td><strong>Codice fiscale cliente</strong><br /><?php echo $prenotazione['codFisc'];?></td>
                        <td><strong>Nome attivita</strong><br /><?php echo $prenotazione['nome'];?></td>
                        <td><strong>Data</strong><br /><?php echo $prenotazione['data'];?></td>
                        <td><strong>Ora inizio</strong><br /><?php echo $oraInizioAttivita;?></td>
                        <td><strong>Ora fine</strong><br /><?php echo $oraFineAttivita;?></td>
                        <td><strong>Prezzo totale</strong><br /><?php echo $prenotazione['prezzoTotale']."&euro;";?></td>
                        <td><strong>Crediti usati</strong><br /><?php echo $prenotazione['creditiUsati'];?></td>
                        <td>
                            <input type="submit" class="button" name="<?php echo $prenotazione['idPrenotazione'];?>" value="MODIFICA" />
                            <input type="submit" class="button" name="<?php echo $prenotazione['idPrenotazione'];?>" onClick="myEvent()" value="ANNULLA" /> 
                            <input type="hidden" name="bottonePremuto" />
                        
                        </td>

                    </tr>
                    
                </table>
        <?php
            }
        ?>
        
        </form>
        
        </div>

        <?php
            if(count($arrayPrenotazioniAttivitaPassate)>0){
                echo "<h3 class=\"titoloImportante alignCenter\">PRENOTAZIONI PASSATE:</h3>";
            }else{
                echo "<h3 class=\"titoloImportante alignCenter\">NON CI SONO ATTIVIT&Agrave; PRENOTATE PASSATE</h3>";
                echo "<span class=\"trePuntini\">...</span>";
                }
        ?>

        <div class="mainContainer">
        <?php
            for($i=0;$i<count($arrayPrenotazioniAttivitaPassate);$i++){
                $prenotazione=$arrayPrenotazioniAttivitaPassate[$i];
                $oraInizioAttivita=substr($prenotazione['oraInizio'],0,5);
                $oraFineAttivita=substr($prenotazione['oraFine'],0,5);
        ?>

                <table class="alignCenter tabella"  align="center">
                    <tr>
                        <td><strong>Codice fiscale cliente</strong><br /><?php echo $prenotazione['codFisc'];?></td>
                        <td><strong>Nome attivita</strong><br /><?php echo $prenotazione['nome'];?></td>
                        <td><strong>Data</strong><br /><?php echo $prenotazione['data'];?></td>
                        <td><strong>Ora inizio</strong><br /><?php echo $oraInizioAttivita;?></td>
                        <td><strong>Ora fine</strong><br /><?php echo $oraFineAttivita;?></td>
                        <td><strong>Prezzo totale</strong><br /><?php echo $prenotazione['prezzoTotale']."&euro;";?></td>
                        <td><strong>Crediti usati</strong><br /><?php echo $prenotazione['creditiUsati'];?></td>
                    </tr>
                    
                </table>
         <?php
            }
         ?>
        </div>
    
        <script>
            function myEvent(){
                var choice =confirm("Confermi di voler annullare la prenotazione ?");
                if(choice == true){
                    document.cookie = "Cancella" + "=" + "Cancella" + "" + "; path=/";  
                }
            }
        </script>
    </body>
</html>