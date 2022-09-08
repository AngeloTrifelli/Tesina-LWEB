<?php 
echo'<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">


    <head>
        <title>Sapienza hotel: Camere e Suite</title>

        <style>
            <?php include "../CSS/camere.css" ?>
        </style>
    
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">    </head>

    <body>
        
        <div id="leftColumn">
                     
            <img id="logo" src="https://upload.wikimedia.org/wikipedia/it/thumb/0/0d/Uniroma1.svg/2560px-Uniroma1.svg.png" alt="Immagine non caricata"/>


            <div id="links">
            

                <a class="item" href="./index.php">HOME</a>
                <br/>

                <a class="item" href="#">RECENSIONI</a>
                <br/>

                <a class="item" href="./registrazioneUtente.php">REGISTRATI</a>
                <br/>
        
                <a class="item" href="./login.php">LOGIN</a>
                <br/>

                <a class="item" href="#">PRENOTA ORA</a>
                <br/>
  
            </div>
            
        </div>

        <div id="rightColumn">
        
            <h1 id="mainTitle">CAMERE E SUITE</h1>

            <div class="camera">

                <h2 class="title">CAMERA SINGOLA STANDARD</h2> 
                <img class="imgcamera" src="../img/camera1.jpg" alt="Immagine non trovata"/>

                <div class="containerDati">
                    <div class="divSx">
                        <ul>
                            <li>Letto matrimoniale per uso singolo</li>
                            <li>Vista mare</li>
                            <li>Aria condizionata</li>
                            <li>WI-FI</li>
                        </ul>
                    </div>
                    <div class="divDx">
                        <ul>
                            <li>TV</li>
                            <li>Mini bar</li>
                            <li>Area fumatori:No</li>
                            <li>Servizio in camera:Si</li>
                        </ul>
                    </div>
               
                </div>
            </div>
        
            <br />
            <br />
            <br />
            <br />

            <hr />

            <div class="camera">

                <h2 class="title">CAMERA DOPPIA STANDARD</h2> 
                <img class="imgcamera" src="../img/camera2.jpg" alt="Immagine non trovata"/>

                <div class="containerDati">
                    <div class="divSx">
                        <ul>
                            <li>Letto matrimoniale per coppia</li>
                            <li>TV</li>
                            <li>Aria condizionata</li>
                            <li>WI-FI</li>
                        </ul>
                    </div>
                    <div class="divDx">
                        <ul>
                            <li>Mini bar</li>
                            <li>Area fumatori:No</li>
                            <li>Servizio in camera:Si</li>
                        </ul>
                    </div>
               
                </div>

            </div>

            <br />
            <br />
            <br />
            <br />
            <br />

            <hr />

            <div class="camera">

                <h2 class="title">SUITE</h2> 
                <img class="imgcamera" src="../img/camera3.jpg" alt="Immagine non trovata"/>


                <div class="containerDati">
                    <div class="divSx">
                        <ul>
                            <li>Letto matrimoniale per coppia</li>
                            <li>Vasca con idromassaggio</li>
                            <li>Aria condizionata</li>
                            <li>WI-FI</li>
                        </ul>
                    </div>
                    <div class="divDx">
                        <ul>
                            <li>TV</li>
                            <li>Mini bar</li>
                            <li>Area fumatori:Si</li>
                            <li>Servizio in camera:Si</li>
                        </ul>
                    </div>
               
                </div>
            </div>


            </div>

            <br />

        </div>






    </body>

</html>