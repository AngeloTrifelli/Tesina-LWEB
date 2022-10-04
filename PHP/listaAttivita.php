<?php
require_once("funzioniGetPHP.php");
require_once("funzioniPHP.php");
session_start();


    if(!isset($_SESSION['codFiscUtenteLoggato'])){
        if(!isset($_SESSION['loginType'])){
            header('Location: intro.php');
        }
    }
    else{
        header('Location: areaUtente.php');
    }

    if(isset($_POST['attivitaSelezionata'])){
        $idAttivita=individuaBottoneIdAttivita();
        $_SESSION['idAttivita']=$idAttivita;
        header('Location: modificaAttivita.php');
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
                <a href="./areaUtente.php">TORNA NELL'AREA UTENTE</a>  
            </div>

            <h1 class="alignCenter">LISTA ATTIVIT&Agrave;</h1>
           
            <div style="width: 18.5%;"></div>
               
        </div>

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

                <p class="alignCenter">
                    <img class="immagine" src="<?php echo $linkImmagine;?>" alt="Immagine non trovata" />
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

                <form action="<?php echo $_SERVER['PHP_SELF']?>"  method="post">

                    <input class="bottone" type="submit"  name="<?php echo "A".$i;?>" value="Modifica">
                    <input type="hidden" name="attivitaSelezionata"/>

                </form> 
 
        </div>
                
        <?php
        }
        ?>
       
    </body>
</html>