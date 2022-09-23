<?php
    session_start();
    if(!isset($_SESSION['codFiscUtenteLoggato'])){
        if(!isset($_SESSION['loginType'])){
            header('Location: intro.php');
        }
    }
    else{
        header('Location: areaUtente.php');
    }

   

    
    echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Lista attivit&agrave;</title>

        <style>
            <?php include "../CSS/listaAttivita.css" ?>
        </style>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
    </head>


    <body>
        <div class="top">

            <div class="topLeft">               
                <a href="./areaUtente.php">TORNA INDIETRO</a>  
            </div>

            <h1 class="alignCenter">LISTA ATTIVIT&Agrave;</h1>
           
            <div style="width: 18.5%;"></div>
               
        </div>

    
         <div class="attivita paragraph">

                <h1 class="title">Palestra</h1>

                <p class="alignCenter">
                    <img class="immagine" src="https://www.destinazioneavventura.it/wp-content/uploads/2019/01/Excelsior-Sport-Hotel-Gala-Milano-Vacanza-Sportive.jpg" alt="Immagine non trovata" />
                </p>

                <p class="articolo">
                    Ubicata all'interno dell'hotel ed equipaggiata con le ultime macchine di Technogym, una palestra interna rappresenta 
                    la soluzione perfetta per mantenersi in forma anche se lontani da casa. Hotel Sapienza vi propone una palestra dotata di:
                    <br />Tapis roulant e cyclette<br />Materassini e ball per aerobica e pilates<br />Pesi
                    e bilancieri di varie dimensioni<br />. Vengono inoltre messi a disposizione gli spogliatoi dotati di armadietti
                    e docce.
                </p>

                <div>

                    <table class="box" align="center">
                        <tr>
                            <td>
                                <div class="miniBox">
                                    <div class="titleBox">
                                        Orario Apertura:
                                    </div>
                            
                                    <div>
                                        06:00
                                    </div>
                                </div>
                            </td>
                            <td>

                                <div class="miniBox">
                                    <div class="titleBox">
                                        Orario Chiusura:
                                    </div>
                            
                                    <div>
                                        22:00
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="miniBox">
                                    <div class="prezzo">
                                        Prezzo all'ora:
                                    </div>
                            
                                    <div>
                                      10&euro;
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="miniBox">
                                    <input class="bottone" type="submit"  name=MODIFICA value="Modifica">
                                </div>                                
                            </td>
                        </tr>
                    </table>
                </div>

                    
                    

            </div>
 
        </div>
    
       
    </body>
</html>