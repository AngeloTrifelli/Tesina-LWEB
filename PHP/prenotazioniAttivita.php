<?php     
    require_once("funzioniGetPHP.php");
    require_once('funzioniPHP.php');

    session_start();

    if(isset($_POST['bottonePremuto'])){
        if(isset($_COOKIE['Cancella'])){
            unset($_COOKIE['Cancella']);
            setcookie('Cancella', '', time() - 3600, '/');
            individuaBottoneAttivitaDaEliminare();
        }  
    }

    if(!isset($_SESSION['codFiscUtenteLoggato'])){
        header('Location: login.php');
        exit();
    }
    

    $arrayPrenotazioniAttivitaUtente=getPrenotazioniAttivitaUtente($_SESSION['codFiscUtenteLoggato']);


    echo'<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">


    <head>
        <title>Sapienza hotel: Attività prenotate</title>

        <style>
            <?php include "../CSS/prenotazioniAttivita.css" ?>
        </style>
    
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet" /> 
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    </head>

    <body>
        
        <div id="leftColumn">
                     
            <img id="logo" src="https://upload.wikimedia.org/wikipedia/it/thumb/0/0d/Uniroma1.svg/2560px-Uniroma1.svg.png" alt="Immagine non caricata"/>


            <div id="links">
               <a class="item" href="../PHP/attivita.php">PRENOTA UN'ATTIVIT&Agrave;</a>               
            </div>

            <div class="comeBack">
              <a class="item" href="../PHP/areaUtente.php">TORNA ALL'AREA PERSONALE</a>
            </div>
            
            
        </div>

        <div id="rightColumn">

            <?php 
            
            if(count($arrayPrenotazioniAttivitaUtente)>0){
                echo "<h1 id=\"mainTitle\">LE TUE ATTIVIT&Agrave; PRENOTATE:</h1>";
            }else{
                echo "<h1 id=\"mainTitle\">NON HAI ATTIVITA ATTUALMENTE PRENOTATE</h1>";
                echo "<span class=\"trePuntini\">...</span>";
                }
            ?>
                <form action="<?php echo $_SERVER['PHP_SELF']?>"  method="post">
            <?php
            for($i=0;$i<count($arrayPrenotazioniAttivitaUtente);$i++){
                    $prenotazioneUtente=$arrayPrenotazioniAttivitaUtente[$i];
                        ?>

                        <table class="box" align="center">

                        <tr>
                    
                            <td>
                                <div class="miniBox">

                                    <div class="titleBox">
                                        Attivit&agrave;:
                                    </div>
                            
                                    <div>
                                        <?php echo $prenotazioneUtente['nome'];  ?>
                                    </div>

                                </div>

                            </td>

                            <td>
                                <div class="miniBox">
                                    <div class="titleBox">
                                        Data prenotazione:
                                    </div>
                            
                                    <div>
                                    <?php
                                            $stringaData = $prenotazioneUtente['data'];
                                            $giorno = substr($stringaData, 8,2);       
                                            $mese = substr($stringaData,5,2 );
                                            $anno = substr($stringaData,0,4 );
                                            echo $giorno."-".$mese."-".$anno;
                                        ?>
                                    </div>
                                    
                                </div>

                            </td>

                            <td>
                                <div class="miniBox">
                                    <div class="titleBox">
                                        Orario apertura:
                                    </div>
                            
                                    <div>
                                        <?php echo substr($prenotazioneUtente['oraInizio'],0,5); ?>
                                    </div>
                                    
                                </div>

                            </td>

                            <td>
                                <div class="miniBox">
                                    <div class="titleBox">
                                        Orario chiusura:
                                    </div>
                            
                                    <div>
                                        <?php echo substr($prenotazioneUtente['oraFine'],0,5); ?>
                                    </div>
                                    
                                </div>

                            </td>

                            <td>
                                <div class="miniBox">

                                    <div class="prezzo">
                                        Prezzo totale:
                                    </div>
                            
                                    <div>
                                        <?php echo $prenotazioneUtente['prezzoTotale']."&euro;"; ?>
                                    </div>
                                    
                                </div>

                            </td>

                            <td>
                                <div class="miniBox">

                                    <div class="prezzo">
                                        Crediti utilizzati:
                                    </div>
                            
                                    <div>
                                        <?php echo $prenotazioneUtente['creditiUsati']; ?>
                                    </div>
                                    
                                </div>

                            </td>

                            <td>
                                    <input type="submit" class="buttonElimina" name="<?php echo $prenotazioneUtente['idPrenotazione'];?>" onClick="myEvent()" value="Elimina" />
                                    <input type="hidden" name="bottonePremuto"/>
                            </td>
        
                        </tr>

                    </table>
                    <?php

                    }
            ?>
                </form>

        </div>
        <script>
            function myEvent(){
                var choice =confirm("Confermi di voler annullare la prenotazione?");
                if(choice == true){
                    document.cookie = "Cancella" + "=" + "Cancella" + "" + "; path=/";  
                }
            }
        </script>
    </body>

</html>