<?php 
echo'<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title>Sapienza hotel: Registrazione Utente</title>

        <style>
            <?php include "../CSS/registrazioneUtente.css" ?>
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
            

            <a class="item" href="./intro.php">HOME</a>
            <br/>
         
            <a class="item" href="./camere.php">CAMERE E SUITE</a>
            <br/> 
      
            <a class="item" href="#">RECENSIONI</a>
            <br/>

            <a class="item" href="#">PRENOTA ORA</a>
            <br/>

            <a class="item" href="./login.php">LOGIN</a>
            <br/>
  
            </div>
            
        </div>


        <div id="rightColumn" >  

            <h1 id="mainTitle">REGISTRAZIONE UTENTE</h1>
        
        

            <div class="containerCentrale">

                <div class="tabella">

                    <div class="riga">

                        <div class="containerColumn">

                            <input class="textInput" type="text" name="nome" placeholder="Inserisci il nome" >

                        </div>


                    <div class="containerColumn">

                        <input class="textInput" type="text" name="cognome" placeholder="Inserisci il cognome" >

                    </div>
                
                </div>

                <div class="riga">
                

                        <div class="containerColumn">

                            <input class="textInput" id="codiceFiscale" type="text" name="codFisc" placeholder="Inserisci il codice fiscale" >

                    </div>


                    <div class="containerData">

                        <h3 class="title"> Inserisci la data di nascita:</h3>
                        <input class="dateInput" type="date" name="dataNascita">

                    </div>

                    </div>

                    <div class="riga">

                        <div class="containerColumn">
                            
                            <input class="textInput" type="text" name="domicilio" placeholder="Inserisci la via" >
                        </div>
                        <div class="containerColumn floatLeft" >

                            <input id="numeroCivicoInput" type="number" name="numCiv" placeholder="N°" >

                        </div>

                        <div class="containerColumn">

                            <input class="textInput" id="email" type="text" name="email" placeholder="Inserisci l'email" >

                        </div>


                    </div>

                    <div class="riga">

                        <div class="containerColumn">

                            <input class="textInput" id="telefono" type="text" name="telefono" placeholder="Inserisci il numero di telefono" >

                        </div>

                    </div>

                    <input type="submit" class="continuaButton button" name="continua" value="Continua">

                </div>
            </div>

        



        </div> 

    </body>

</html>
