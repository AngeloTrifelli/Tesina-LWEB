<?php 
echo'<?xml version="1.0" encoding="UTF-8"?>';
require_once("funzioniGetPHP.php");

session_start();


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">


    <head>
        <title>Sapienza hotel: Attività prenotate</title>

        <style>
            <?php include "../CSS/prenotazioniAttivita.css" ?>
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

               <a class="item" href="../PHP/attivita.php">TORNA INDIETRO</a>

            </div>
            
            
        </div>

        <div id="rightColumn">
        
            <h1 id="mainTitle">LE TUE ATTIVIT&Agrave; PRENOTATE:</h1>

            <?php 
            
            $xmlStringAttivita= "";

             foreach(file("../XML/Attivita.xml") as $node){

                $xmlStringAttivita .= trim($node);

            }

            $docAttivita = new DOMDocument();
            $docAttivita->loadXML($xmlStringAttivita);

            $listaAttivita = $docAttivita->documentElement->childNodes;

            $numAttivita= numAttivita();
            $i=0;
            while($i<$numAttivita){
                $attivita=$listaAttivita->item($i);
                
                $listaPrenotazioni=$attivita->getElementsByTagName("prenotazione");
                $numPrenotazioni= $listaPrenotazioni->length;

                $testoNomeAttivita=array();
                $testoDataPrenotazione=array();
                $testoOrarioInizio=array();
                $testoOrarioFine=array();
                $testoPrezzo=array();
                $testoCreditiUsati=array();
                for($j=0; $j<$numPrenotazioni; $j++){
                    $elemPrenotazione=$attivita->getElementsByTagName("prenotazione")->item($j); //prenotazione utente
                   
                    if(($elemPrenotazione->firstChild->nextSibling->textContent)== $_SESSION['codFiscUtenteLoggato']){
                        $testoNomeAttivita[$j]= $attivita->firstChild->textContent;
                        $testoDataPrenotazione[$j]=$elemPrenotazione->getElementsByTagName("data")->item(0)->textContent;
                        $testoOrarioInizio[$j]=$elemPrenotazione->getElementsByTagName("oraInizio")->item(0)->textContent;
                        $testoOrarioFine[$j]=$elemPrenotazione->getElementsByTagName("oraFine")->item(0)->textContent;
                        $testoPrezzo[$j]=$elemPrenotazione->getElementsByTagName("prezzoTotale")->item(0)->textContent;
                        $testoCreditiUsati[$j]=$elemPrenotazione->getElementsByTagName("creditiUsati")->item(0)->textContent;
                        ?>

                        <table class="box" align="center">

                        <tr>
                    
                            <td>
                                <div class="miniBox">

                                    <div class="titleBox">
                                        Attivit&agrave;:
                                    </div>
                            
                                    <div>
                                        <?php echo $testoNomeAttivita[$j]; ?>
                                    </div>

                                </div>

                            </td>

                            <td>
                                <div class="miniBox">
                                    <div class="titleBox">
                                        Data prenotazione:
                                    </div>
                            
                                    <div>
                                        <?php echo $testoDataPrenotazione[$j]; ?>
                                    </div>
                                    
                                </div>

                            </td>

                            <td>
                                <div class="miniBox">
                                    <div class="titleBox">
                                        Orario Apertura:
                                    </div>
                            
                                    <div>
                                        <?php echo $testoOrarioInizio[$j]; ?>
                                    </div>
                                    
                                </div>

                            </td>

                            <td>
                                <div class="miniBox">
                                    <div class="titleBox">
                                        Orario Chiusura:
                                    </div>
                            
                                    <div>
                                        <?php echo $testoOrarioFine[$j]; ?>
                                    </div>
                                    
                                </div>

                            </td>

                            <td>
                                <div class="miniBox">

                                    <div class="prezzo">
                                        Prezzo totale:
                                    </div>
                            
                                    <div>
                                        <?php echo $testoPrezzo[$j]."&euro;"; ?>
                                    </div>
                                    
                                </div>

                            </td>

                            <td>
                                <div class="miniBox">

                                    <div class="prezzo">
                                        Crediti utilizzati:
                                    </div>
                            
                                    <div>
                                        <?php echo $testoCreditiUsati[$j]; ?>
                                    </div>
                                    
                                </div>

                            </td>
        
                        </tr>

                    </table>
                    <?php

                    }
                }

                $i++;
            }

            ?>

        </div>

    </body>

</html>