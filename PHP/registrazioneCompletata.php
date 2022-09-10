<?php

    session_start();
    if(!isset($_SESSION['username'])){        //Ora controllo questa variabile di sessione per verificare che l'utente sia arrivato in questa pagina passando per registrazioneFinale.php
        header('Location: registrazioneUtente.php');
        exit();
    }
    else{
        unset($_SESSION);
        session_destroy();
        header( "refresh:5;url=login.php" );
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title>Sapienza hotel: Registrazione Completata</title>

        <style>
            <?php include "../CSS/registrazioneCompletata.css" ?>
    </style>
    
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet" /> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
 
      </head>
    
    <body>

    <div id="leftColumn">
                     
        <img id="logo" src="https://upload.wikimedia.org/wikipedia/it/thumb/0/0d/Uniroma1.svg/2560px-Uniroma1.svg.png" alt="Immagine non caricata"/>
                   
    </div>
         
    <div id="rightColumn" >  

       

                <div class="containerCentrale">

                    <h1 id="mainTitle">REGISTRAZIONE COMPLETATA <i class="fa-solid fa-check"></i></h1>

                    

                        <h3 id="title">Verrai reindirizzato alla pagina di Login tra 5 secondi...</h3>

                    
                
                </div>

    </div>




    </body>

</html>