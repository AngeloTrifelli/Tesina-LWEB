<?php     
    require_once("funzioniGetPHP.php");
    require_once("funzioniPHP.php");
    session_start();

    if(isset($_SESSION['codFiscUtenteLoggato'])){
        $temp=$_SESSION['soggiornoAttivo'];
        if($temp!="null"){
            if($temp['statoSoggiorno']!= "Approvato"){
                header("Location: areaUtente.php");
                exit();
            }
        }
        else{
            header('Location: areaUtente.php');
            exit();
        }
    }
    else{
        header('Location: login.php');
        exit();
    }

    if(isset($_POST['attivitaSelezionata'])){
        $idAttivita=individuaBottoneIdAttivita();
        $_SESSION['idAttivita']=$idAttivita;
        header('Location: prenotaAttivita.php');
        exit();
    }

    echo'<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">


    <head>
        <title>Sapienza hotel: Attività</title>

        <style>
            <?php include "../CSS/attivita.css" ?>
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

                <a class="item" href="../PHP/prenotazioniAttivita.php">VISUALIZZA PRENOTAZIONI</a>
                <br/>

            </div>

            <div class="comeBack">

              <a class="item" href="../PHP/areaUtente.php">TORNA ALL'AREA PERSONALE</a>

            </div>
            
            
        </div>

        <div id="rightColumn">
        
            <h1 id="mainTitle">ATTIVIT&agrave;</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']?>"  method="post">
            <?php 
            $numAttivita= numAttivita();

            for ($i=1 ; $i<= $numAttivita ; $i++){

                $arrayDati=getDatiAttivita("A".$i);

                $nome=$arrayDati['nome'];
                $descrizione=$arrayDati['descrizione'];
                $linkImmagine= $arrayDati['linkImmagine'];
                $oraApertura=substr($arrayDati['oraApertura'],0,5);
                $oraChiusura=substr($arrayDati['oraChiusura'],0,5);
                $prezzoOrario=$arrayDati['prezzoOrario'];
          
    ?>

            <div class="attivita paragraph">

                <h1 class="title"><?php echo $nome;?></h1>

                <p>

                    <img class="immagine" src="<?php echo $linkImmagine;?>" alt="Immagine non trovata" align="middle">
            
                </p>

                <p class="articolo">
                    <?php echo $descrizione;?>
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
                                        <?php echo $oraApertura;?>
                                    </div>

                                </div>

                            </td>

                            <td>
                                <div class="miniBox">
                                    <div class="titleBox">
                                        Orario Chiusura:
                                    </div>
                            
                                    <div>
                                        <?php echo $oraChiusura;?>
                                    </div>
                                    
                                </div>

                            </td>

                            <td>
                                <div class="miniBox">

                                    <div class="prezzo">
                                        Prezzo all'ora:
                                    </div>
                            
                                    <div>
                                      <?php echo $prezzoOrario."&euro;";?>
                                    </div>
                                    
                                </div>

                            </td>
        
                        </tr>

                    </table>

                </div>

                    <input class="bottone" type="submit"  name="<?php echo "A".$i;?>" value="Prenota">
                    <input type="hidden" name="attivitaSelezionata"/>

            </div>

            <?php
        }
    ?>
           </form> 

            
        </div>


    </body>

</html>
            