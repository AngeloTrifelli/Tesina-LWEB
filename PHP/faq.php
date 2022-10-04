<?php
    echo'<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title>Domande</title>

        <style type="text/css">
            <?php include "../CSS/faq.css" ?>
         </style>

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&amp;display=swap" rel="stylesheet" />  

        <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet" />

      </head>

    <body>
        <div class="top">
            <div class="topLeft">               
                <a href="./intro.php">TORNA INDIETRO</a>  
            </div>      
            <h1 class="alignCenter">FAQ</h1>
            <div style="width: 18.5%;"></div>                              
        </div>

        <section>
            <div class="container">
                <div class="innerContainer">
                    <div class="innerContainer-item" id="domanda1">
                        <a class="innerContainer-link" href="#domanda1">
                            <div class="domanda">
                                Quanto è lontano l'hotel dal centro di latina?
                            </div>
                            <button type="submit" class="button" name="PROVA">
                                MODIFICA FAQ
                            </button>
                            <button type="submit" class="button" name="PROVA">
                                RIMUOVI FAQ
                            </button>
                            <i class="icon ion-md-add"></i>
                            <i class="icon ion-md-remove"></i>
                        </a>                        

                        <div class="risposta">
                            Il centro si può raggiungere facilmente in 5 minuti a piedi
                        </div>
                    </div>                    
                </div>
            </div>

            <div class="container">
                <div class="innerContainer">
                    <div class="innerContainer-item" id="domanda2">
                        <a class="innerContainer-link" href="#domanda2">
                            SECONDA DOMANDA
                            <i class="icon ion-md-add"></i>
                            <i class="icon ion-md-remove"></i>
                        </a>                        

                        <div class="risposta">
                            TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST
                            TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST
                        </div>
                    </div>                    
                </div>
            </div>
        </section>
                                        
    </body>
</html> 
