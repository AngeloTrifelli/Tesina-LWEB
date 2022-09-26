<?php 
    echo'<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title>Recensioni</title>

        <style type="text/css">
            <?php include "../CSS/recensioni.css" ?>
         </style>

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&amp;display=swap" rel="stylesheet" />  
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      </head>

    <body>
        <div class="top">
            <div class="topLeft">               
                <a href="./intro.php">TORNA INDIETRO</a>  
            </div>  
            <div>
                <input type="submit" id="fakeLink" value="AGGIUNGI RECENSIONE" />
            </div>                         
        </div>

        <div class="mainContainer">
            <h1 id="mainTitle">RECENSIONI</h1>
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
                <h3 class="specialText">Visualizza le tue recensioni:</h3>                               
                <input type="submit" class="button" name="VISUALIZZA" value="VISUALIZZA" />          
            </div>
        </div>

        <div class="mainContainer minHeight">
           <div class="topBox">
                <div class="miniBox" style="padding-top: 1.2% ;">
                    <span class="larger"><span class="fa fa-star checked"></span><span>
                    <span class="fa fa-star checked larger"></span>
                    <span class="fa fa-star checked larger"></span>
                    <span class="fa fa-star checked larger"></span>
                    <span class="fa fa-star checked larger"></span>
                </div>
                <div class="miniBox specialText">
                    Autore: Giovanni Rossi
                </div>                
                <div class="miniBox specialText">
                    Categoria: Servizi
                </div>
           </div>
           <div class="middleBox">
                Soggiorno fantastico! Il ristorante offre sempre piatti di immensa qualità ed il personale è 
                estremamente professionale e sempre disponibile.                      
            </div>
            <div class="bottomBox">
                <span class="specialText" style="color: white;">Utilit&agrave; totale: 50</span>
                <span class="specialText" style="color: white;">Accordo totale: 100</span>
                <input type="submit" class="specialButton" name="VISUALIZZA" value="VISUALIZZA RISPOSTE" />          
                <input type="submit" class="specialButton" name="GIUDIZIO" value="INSERISCI GIUDIZIO" />  
            </div>
        </div>

        <div class="mainContainer">
        <div class="topBox">
                <div class="miniBox" style="padding-top: 1.2% ;">
                    <span class="larger"><span class="fa fa-star checked"></span><span>
                    <span class="fa fa-star checked larger"></span>
                    <span class="fa fa-star checked larger"></span>
                    <span class="fa fa-star  larger"></span>
                    <span class="fa fa-star  larger"></span>
                </div>
                <div class="miniBox specialText">
                    Autore: Giovanni Rossi
                </div>                
                <div class="miniBox specialText">
                    Categoria: Generale
                </div>
           </div>
           <div class="middleBox">
                BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA 
                BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA BLA                       
            </div>
            <div class="bottomBox">
                <span class="specialText" style="color: white;">Utilit&agrave; totale: 2</span>
                <span class="specialText" style="color: white;">Accordo totale: 10</span>
                <input type="submit" class="specialButton" name="VISUALIZZA" value="VISUALIZZA RISPOSTE" />          
                <input type="submit" class="specialButton" name="GIUDIZIO" value="INSERISCI GIUDIZIO" />  
            </div>       
        </div>

    
            
    </body>
</html>