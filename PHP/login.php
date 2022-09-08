<?php 
echo'<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title>Sapienza hotel: Registrazione Utente</title>
        <style>
            <?php include "../CSS/login.css" ?>
        </style>
    
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">  
      </head>
    
    <body>
        
        
        <div id="leftColumn">
                     
            <img id="logo" src="https://upload.wikimedia.org/wikipedia/it/thumb/0/0d/Uniroma1.svg/2560px-Uniroma1.svg.png" alt="Immagine non caricata"/>


            <div id="links">
            

            <a class="item" href="/index.html">HOME</a>
            <br/>
         
            <a class="item" href="/HTML/camere.html">CAMERE E SUITE</a>
            <br/> 
          
            <a class="item" href="#">RECENSIONI</a>
            <br/>

            <a class="item" href="#">PRENOTA ORA</a>
            <br/>

            <a class="item" href="/HTML/registrazioneUtente.html">REGISTRATI</a>
            <br/>

  
            </div>
            
        </div>


        <div id="rightColumn" >  

            <h1 id="mainTitle">LOGIN</h1>

            <div class="containerDati">

                <div class="divSx">

                    <input class="textInput" type="text" name="usernames" placeholder="Inserisci l'username" >

                    <br>

                    <input class="textInput" type="text" name="password" placeholder="Inserisci la password" >

                </div>

                <div class="divDx">    
                    
                    <p class="title">Che tipo di utente sei?</p>
                    <br />
                    <input type="radio" id="cliente" name="type" value="cliente">
                    <label for="cliente">Cliente</label><br>
                    <input type="radio" id="admin" name="type" value="admin">
                    <label for="admin">Admin</label><br>
                    <input type="radio" id="concierge" name="type" value="concierge">
                    <label for="concierge">Concierge</label>

                </div>
                        

                

            </div> 

            <input type="submit" class="continuaButton button" name="accedi" value="Accedi">

            <div id="registrazione">
                <a  href="/HTML/registrazioneUtente.html">Non sei ancora registrato? Clicca qui!</a>
            </div>

        </div>

    </body>

</html>
