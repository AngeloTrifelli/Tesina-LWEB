<?php 
    session_start();
    if(isset($_SESSION['codFiscUtenteLoggato'])){
        $temp = $_SESSION['soggiornoAttivo'];
        if($temp != "null"){
            if($temp['statoSoggiorno'] != "Approvato"){
                header('Location: areaUtente.php');
            }
        }
        else{
            header('Location: prenotaOra.php');
        }
    }
    else{
        header('Location: login.php');
    }

    


    echo'<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title>Domande</title>

        <style type="text/css">
            <?php include "../CSS/domande.css" ?>
         </style>

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&amp;display=swap" rel="stylesheet" />  
      </head>

    <body>
        <div class="top">
            <div class="topLeft">               
                <a href="./areaUtente.php">TORNA INDIETRO</a>  
            </div>      
            <div>
                <input type="submit" id="fakeLink" value="AGGIUNGI DOMANDA" />
            </div>                     
        </div>

        <div class="mainContainer">
            <h1 id="mainTitle">DOMANDE</h1>
            <div class="row">
                <h3 class="specialText">Scegli la categoria:</h3>
                <select id="selectInput">
                            <option disabled selected value="Scegli">-- Scegli -- </option>
                            <option value="Generale">Generale</option>
                            <option value="Camere">Camere</option>
                            <option value="Servizi">Servizi</option>
                </select>                 
                <input type="submit" class="button" name="CONFERMA" value="CONFERMA" />      
            </div>
            <div class="row">
                <h3 class="specialText">Visualizza le tue domande:</h3>                               
                <input type="submit" class="button" name="VISUALIZZA" value="VISUALIZZA" />          
            </div>
        </div>

        <div class="mainContainer minHeight">
           <div class="topBox">
                <div class="miniBox specialText">
                    Giovanni Rossi
                </div>
                <div class="miniBox specialText">
                        Categoria: Servizio in Camera
                </div>
           </div>
           <div class="middleBox">
                La palestra e' dotata di un phon?                       
            </div>
            <div class="bottomBox">
                <input type="submit" class="specialButton" name="VISUALIZZA" value="VISUALIZZA RISPOSTE" />          
            </div>
        </div>

        <div class="mainContainer">
           <div class="topBox">
                <div class="miniBox specialText">
                    Giovanni Rossi
                </div>
                <div class="miniBox specialText">
                        Categoria: Servizio in Camera
                </div>                
           </div>           
        </div>

    

            
    </body>
</html>
