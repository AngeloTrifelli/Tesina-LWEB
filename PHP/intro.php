<?php 
    session_start();

    if(isset($_POST['LOGOUT'])){
        if($_SESSION['loginType'] == "Cliente"){
            unset($_SESSION['codFiscUtenteLoggato']);
            unset($_SESSION['soggiornoAttivo']);
            unset($_SESSION['loginType']);
        }
        else{
            unset($_SESSION['loginType']);
        }
    }
    echo'<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title>Sapienza hotel</title>

        <style type="text/css">
            <?php include "../CSS/intro.css" ?>
         </style>

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&amp;display=swap" rel="stylesheet" />  
      </head>

    <body>
        
                <div id="leftColumn">
                     
                    <img id="logo" src="https://upload.wikimedia.org/wikipedia/it/thumb/0/0d/Uniroma1.svg/2560px-Uniroma1.svg.png" alt="Immagine non caricata"/>


                    <div id="links">
                    

                    <a class="item" href="#index">HOME</a>
                    <br/>
                 
                    <a class="item" href="./camere.php">CAMERE E SUITE</a>
                    <br/> 
                  
                    <a class="item" href="#attivita">ATTIVIT&agrave;</a>
                    <br/>

                    <a class="item" href="#doveSiamo">DOVE SIAMO?</a>
                    <br/>
               
                    <a class="item" href="#contatti">CONTATTI</a>
                    <br/>
              
                    <a class="item" href="./recensioni.php">RECENSIONI</a>
                    <br/>

                    <a class="item" href="./faq.php">FAQ</a>
                    <br />

                    <?php
                        if(!isset($_SESSION['codFiscUtenteLoggato'])){
                    ?>
                            <a class="item" href="./prenotaOra.php">PRENOTA ORA</a>
                            <br/>

                            <a class="item" href="./registrazioneUtente.php">REGISTRATI</a>
                            <br/>

                            <a class="item" href="./login.php">LOGIN</a>
                            <br/>
                    <?php
                        }
                        else{
                            if($_SESSION['soggiornoAttivo'] == "null"){
                                echo '
                                <a class="item" href="./prenotaOra.php">PRENOTA ORA</a>
                                <br />';
                            }
                    ?>
                            <a class="item" href="./areaUtente.php">AREA PERSONALE</a>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                <br />
                                <input type="submit" class="logoutButton" value="LOGOUT" name="LOGOUT" />
                            </form>
                    <?php
                        }
                    ?>
                    
          
                    </div>
                    
                </div>
            <div id="rightColumn">
                    
                <div class="content"></div>

            <div id="attivita">

                <h2 class="title">ATTIVIT&agrave;</h2>

                <div id="immagini">

                    <img id="img1" src="../img/gym.jpg" alt="Immagine non trovata"/>
                    <img id="img2"  src="../img/spa.jpg" alt="Immagine non trovata"/>
                    <img id="img3"  src="../img/parco.jpg" alt="Immagine non trovata"/>

                </div>
                    <div id="paragraphAttivita">
                    
                        Scopri tutte le fantastiche attività che il nostro hotel dispone. Qui potrai trovare una fantastica
                        palestra per poterti allenare in tuttà tranquillità assortita con piu di un centinaio di attrezzi 
                        di ultima generazione, una spa composta da 5 piscine di diverse dimensioni per poterti rilassare e 
                        di zone ricreative dove poter lasciare i tuoi bambini divertirsi con giochi e attività 
                        organizzate dai nostri tutor.    

                    </div>

            </div>

               <div id="doveSiamo">
                <h2 class="title">DOVE SIAMO?</h2>
               </div>
                    <img id="map" src="../img/map.JPG" alt="Immagine non trovata"/>
                    <div id="paragraphMap">
                        L'edificio si trova esattamente adiacente alla facoltà di Ingegneria civile e industriale di Latina.
                        <br/>
                        Per raggiungerlo basta semplicemente arrivare alla stazione di Latina tramite treno, prendere un bus CSC da Latina Scalo 
                        verso Latina(partono ogni 15 minuti circa dalla stazione) e scendere alla fermata presso ponte silli (la fermata si trova davanti
                        un ottificio e si trova apena usciti dall'epitaffio). <br/>
                        Dopodichè proseguite per 600 metri in avanti, al secondo semaforo che incrociate svoltate a sinistra. 
                        Proseguite per altr 100 metri e risvoltate a sinistra.  <br/>La struttura sarà subito alla vostra sinistra.
                    </div>

                
                <hr>
                <div class="articolo">
                    <h2 class="title">CONTATTI</h2>
        
                    <div id="contatti" >
                        <div class="info">
                            <span>Latina, IT</span>
                            <br />
                            <span>Phone: 0773 000000</span>
                            <br />
                            <span>Email: mail@mail.com </span>
                        </div>
                    </div>
                </div>        
            </div>
    

    </body>
</html>
