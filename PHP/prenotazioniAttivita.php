<?php     
    require_once("funzioniGetPHP.php");
    require_once('funzioniPHP.php');

    session_start();

    if(isset($_POST['bottonePremuto'])){
        individuaBottoneAttivitaDaEliminare();
    }

    if(isset($_SESSION['codFiscUtenteLoggato'])){
        $temp=$_SESSION['soggiornoAttivo'];
        if($temp!="null"){
            if($temp['statoSoggiorno']!= "Approvato"){
                header("Location: areaUtente.php");
                exit();
            }
        }
        else{
            header('Location: areaUtente.php');
            exit();
        }
    }
    else{
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
                                        <?php echo $prenotazioneUtente['data']; ?>
                                    </div>
                                    
                                </div>

                            </td>

                            <td>
                                <div class="miniBox">
                                    <div class="titleBox">
                                        Orario apertura:
                                    </div>
                            
                                    <div>
                                        <?php echo $prenotazioneUtente['oraInizio']; ?>
                                    </div>
                                    
                                </div>

                            </td>

                            <td>
                                <div class="miniBox">
                                    <div class="titleBox">
                                        Orario chiusura:
                                    </div>
                            
                                    <div>
                                        <?php echo $prenotazioneUtente['oraFine']; ?>
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
                            <form action="<?php echo $_SERVER['PHP_SELF']?>"  method="post">

                                    <input type="submit" class="buttonElimina" name="<?php echo $prenotazioneUtente['idPrenotazione'];?>" value="Elimina" />
                                    <input type="hidden" name="bottonePremuto"/>
 
                            </form>

                            </td>
        
                        </tr>

                    </table>
                    <?php

                    }
            ?>

        </div>

    </body>

</html>