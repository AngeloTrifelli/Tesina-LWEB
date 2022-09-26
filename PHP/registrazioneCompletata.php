<?php

    session_start();
    if(!isset($_SESSION['username']) && !isset($_SESSION['soggiornoAttivo'])){        
        header('Location: registrazioneUtente.php');
        exit();
    }
    else{
        if(isset($_SESSION['username'])){
            unset($_SESSION['nome']);
            unset($_SESSION['cognome']);
            unset($_SESSION['codFisc']);
            unset($_SESSION['dataNascita']);
            unset($_SESSION['indirizzo']);
            unset($_SESSION['telefono']);
            unset($_SESSION['email']);
            unset($_SESSION['numeroCarta']);
            header( "refresh:5;url=login.php" );
        }
        else{
            header("refresh:5;url=areaUtente.php");
        }
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title>Sapienza hotel: Successo</title>

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
                    <h1 id="mainTitle">
                    <?php
                        if(isset($_SESSION['username'])){
                            echo 'REGISTRAZIONE COMPLETATA';
                        }
                        else{
                            echo 'PRENOTAZIONE COMPLETATA';
                        }
                    ?>
                    <i class="fa-solid fa-check"></i>
                    </h1>

                    <h3 id="title">
                    <?php
                        if(isset($_SESSION['username'])){
                            echo  'Verrai reindirizzato alla pagina di Login tra 5 secondi...';
                        }
                        else{
                            echo 'Verrai reindirizzato alla pagina personale tra 5 secondi...';
                        }
                    ?>
                    </h3>
                </div>

    </div>




    </body>

</html>